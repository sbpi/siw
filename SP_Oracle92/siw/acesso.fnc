create or replace function Acesso
  (p_solicitacao in number,
   p_usuario      in number,
   p_tramite      in number default null
  ) return number is
/**********************************************************************************
* Nome      : Acesso
* Finalidade: Verificar se o usu�rio tem acesso a uma solicitacao, de acordo com os par�metros informados
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      :  14/10/2003, 10:35
* Par�metros:
*    p_solicitacao : chave prim�ria de SIW_SOLICITACAO
*    p_usuario   : chave de acesso do usu�rio
* Retorno: campo do tipo bit
*   16: Se a solicita��o deve aparecer na mesa de trabalho do usu�rio
*    8: Se o usu�rio � gestor do m�dulo � qual a solicita��o pertence
*       Outra possibilidade �:
*          o usu�rio ser respons�vel por uma etapa de um projeto
*          o usu�rio ser titular ou substituto da unidade respons�vel por uma etapa de um projeto
*          o usu�rio ser respons�vel por alguma quest�o de um projeto (risco ou problema)
*    4: Se o usu�rio � o respons�vel pela unidade de lota��o do solicitante da solicita��o
*       Obs: somente se o tr�mite for cumprido pela chefia imediata
*       Outra possibilidade � usu�rio cumprir algum tr�mite no servi�o
*    2: Se o usu�rio � o solicitante da solicitacao ou se � um interessado na sua execu��o
*    1: Se o usu�rio � o cadastrador ou executor da solicita��o ou � est� lotado na unidade de cadastramento
*       Outra possibilidade �:
*          o usu�rio ser representante do contrato
*          o usu�rio ser representante do projeto
*          a solicita��o ser do m�dulo de planejamento estrat�gico
*    0: Se o usu�rio n�o tem acesso � solicita��o
*    Se o usu�rio enquadrar-se em mais de uma das situa��es acima, o retorno ser� a
*    soma das situa��es. Assim,
*    3  - se for cadastrador e solicitante/interessado
*    5  - se for cadastrador e chefe da unidade
*    6  - se for solicitante e chefe da unidade
*    7  - se for cadastrador, solicitante e chefe da unidade
*    9  - se for cadastrador e gestor
*    10 - se for solicitante e gestor
*    11 - se for cadastrador, solicitante e gestor
*    12 - se for chefe da unidade e gestor
*    13 - se for cadastrador, chefe da unidade e gestor
*    14 - se for solicitante, chefe da unidade e gestor
*    15 - se for cadastrador, solicitante, chefe da unidade e gestor
*    16 a 31 - se o usu�rio deve cumprir o tr�mite em que a solicita��o est�
***********************************************************************************/
  w_interno                co_tipo_vinculo.interno%type;
  w_sq_servico             siw_menu.sq_menu%type;
  w_acesso_geral           siw_menu.acesso_geral%type;
  w_modulo                 siw_menu.sq_modulo%type;
  w_sg_modulo              siw_modulo.sigla%type;
  w_sigla                  siw_menu.sigla%type;
  w_destinatario           siw_menu.destinatario%type;
  w_username               sg_autenticacao.sq_pessoa%type;
  w_sq_unidade_lotacao     sg_autenticacao.sq_unidade%type;
  w_gestor_seguranca       sg_autenticacao.gestor_seguranca%type;  
  w_gestor_sistema         sg_autenticacao.gestor_sistema%type;
  w_sq_unidade_executora   siw_menu.sq_unid_executora%type;        -- Unidade executora do servi�o
  w_consulta_opiniao       siw_menu.consulta_opiniao%type;
  w_envia_email            siw_menu.envia_email%type;
  w_exibe_relatorio        siw_menu.exibe_relatorio%type;
  w_vinculacao             siw_menu.vinculacao%type;
  w_sq_siw_tramite         siw_solicitacao.sq_siw_tramite%type;
  w_cadastrador            siw_solicitacao.cadastrador%type;
  w_unidade_solicitante    siw_solicitacao.sq_unidade%type;
  w_sq_pessoa_executor     siw_solicitacao.executor%type;
  w_opiniao_solicitante    siw_solicitacao.opiniao%type;
  w_ordem                  siw_tramite.ordem%type;
  w_sq_cc                  siw_solicitacao.sq_cc%type;
  w_sigla_situacao         siw_tramite.sigla%type;
  w_ativo                  siw_tramite.ativo%type;
  w_usuario_ativo          sg_autenticacao.ativo%type;
  w_chefia_imediata        siw_tramite.chefia_imediata%type;
  w_sq_pessoa_titular      eo_unidade_resp.sq_pessoa%type;         -- Titular da unidade solicitante
  w_sq_pessoa_substituto   eo_unidade_resp.sq_pessoa%type;         -- Substituto da unidade solicitante
  w_sq_endereco_unidade    eo_unidade.sq_pessoa_endereco%type;
  w_solicitante            number(18);                             -- Solicitante
  w_unidade_beneficiario   number(18);
  w_existe                 number(18);
  w_unidade_atual          number(18);
  w_chefe_beneficiario     number(18);
  w_executor               number(18);
  Result                   number := 0;
  w_unidade_resp           number(18);

  cursor c_unidade (p_unidade in number) is
     select pt.sq_unidade, a.sq_unidade_pai, Nvl(pt.sq_pessoa, -1) as sq_pessoa_titular,
            Nvl(ps.sq_pessoa, -1) as sq_pessoa_substituto
      from eo_unidade a
           left join (select b.sq_unidade, a.sq_pessoa, a.nome_resumido as nome
                       from co_pessoa                  a
                            inner join eo_unidade_resp b on (a.sq_pessoa       = b.sq_pessoa and
                                                             b.tipo_respons    = 'T' and
                                                             b.fim             is null and
                                                             b.sq_unidade      = p_unidade
                                                            )
                     ) pt on (a.sq_unidade  = pt.sq_unidade)
           left join (select b.sq_unidade, a.sq_pessoa, nome_resumido as nome
                        from co_pessoa                  a
                             inner join eo_unidade_resp b on (a.sq_pessoa      = b.sq_pessoa and 
                                                              b.tipo_respons   = 'S' and 
                                                              b.fim            is null and 
                                                              b.sq_unidade     = p_unidade 
                                                             )
                     ) ps on (a.sq_unidade  = ps.sq_unidade)
     where a.sq_unidade  = p_unidade;
begin

 -- Verifica se a solicita��o e o usu�rio informados existem
 select count(*) into w_existe from siw_solicitacao where sq_siw_solicitacao = p_solicitacao;
 If w_existe = 0 Then
    Result := 0;
    Return (Result);
 End If;
 
 select count(*) into w_existe from co_pessoa where sq_pessoa = p_usuario;
 If w_existe = 0 Then
    Result := 0;
    Return (Result);
 End If;
 
 -- Recupera as informa��es da op��o � qual a solicita��o pertence
 select a.acesso_geral, a.sq_menu, a.sq_modulo, a.sigla, e.destinatario,
        a1.sigla,
        b.sq_pessoa, b.sq_unidade, b.gestor_seguranca, b.gestor_sistema, b.ativo as usuario_ativo,
        a.sq_unid_executora, a.consulta_opiniao, a.envia_email, a.exibe_relatorio, a.vinculacao, 
        d.sq_siw_tramite, d.solicitante, d.cadastrador, d.sq_unidade, d.executor, d.opiniao, 
        case when d.sq_cc is not null 
             then d.sq_cc
             else case when i.sq_cc is not null
                       then i.sq_cc
                       else j.sq_cc
                  end
        end as sq_cc,
        e.ordem, e.sigla, e.ativo, e.chefia_imediata,
        Nvl(f.sq_pessoa,-1), Nvl(g.sq_pessoa,-1),
        h.sq_pessoa_endereco, d.executor,
        coalesce(k1.sq_unidade, l1.sq_unidade,m1.sq_unidade,n1.sq_unidade,d.sq_unidade) --d.sq_unidade deve sempre ser a �ltima op��o
   into w_acesso_geral, w_sq_servico, w_modulo, w_sigla, w_destinatario,
        w_sg_modulo,
        w_username, w_sq_unidade_lotacao, w_gestor_seguranca, w_gestor_sistema, w_usuario_ativo,
        w_sq_unidade_executora, w_consulta_opiniao, w_envia_email, w_exibe_relatorio, w_vinculacao,
        w_sq_siw_tramite, w_solicitante, w_cadastrador, w_unidade_solicitante, w_sq_pessoa_executor, w_opiniao_solicitante, w_sq_cc,
        w_ordem, w_sigla_situacao, w_ativo, w_chefia_imediata,
        w_sq_pessoa_titular, w_sq_pessoa_substituto,
        w_sq_endereco_unidade, w_executor,
        w_unidade_resp
   from sg_autenticacao                     b,
        siw_solicitacao                     d
        inner   join siw_menu               a  on (a.sq_menu                = d.sq_menu)
          inner join siw_modulo             a1 on (a.sq_modulo              = a1.sq_modulo)
        inner   join siw_tramite            e  on (e.sq_siw_tramite         = coalesce(p_tramite, d.sq_siw_tramite))
        inner   join eo_unidade             h  on (d.sq_unidade             = h.sq_unidade)
        left    join eo_unidade_resp        f  on (d.sq_unidade             = f.sq_unidade and
                                                   f.tipo_respons           = 'T'          and
                                                   f.fim                    is null
                                                  )
        left    join eo_unidade_resp        g  on (d.sq_unidade             = g.sq_unidade and
                                                   g.tipo_respons           = 'S'          and
                                                   g.fim                    is null
                                                  )
        left    join siw_solicitacao        i  on (d.sq_solic_pai           = i.sq_siw_solicitacao)
          left  join siw_solicitacao        j  on (i.sq_solic_pai           = j.sq_siw_solicitacao)
        left    join pj_projeto             k  on (d.sq_siw_solicitacao     = k.sq_siw_solicitacao)
          left  join eo_unidade             k1 on (k.sq_unidade_resp        = k1.sq_unidade)
        left    join gd_demanda             l  on (d.sq_siw_solicitacao     = l.sq_siw_solicitacao)
          left  join eo_unidade             l1 on (l.sq_unidade_resp        = l1.sq_unidade)
        left    join pe_programa            m  on (d.sq_siw_solicitacao     = m.sq_siw_solicitacao)
          left  join eo_unidade             m1 on (m.sq_unidade_resp        = m1.sq_unidade)
        left    join cl_solicitacao         n  on (d.sq_siw_solicitacao     = n.sq_siw_solicitacao)
          left  join eo_unidade             n1 on (n.sq_unidade             = n1.sq_unidade)
  where d.sq_siw_solicitacao     = p_solicitacao
    and b.sq_pessoa              = p_usuario;
  
 select b.interno
   into w_interno
   from co_pessoa                  a
        inner join co_tipo_vinculo b on (a.sq_tipo_vinculo = b.sq_tipo_vinculo)
  where a.sq_pessoa = p_usuario;
   
 Result := 0;
 
 -- Verifica se o usu�rio est� ativo
 If w_usuario_ativo = 'N' Then
   -- Se n�o estiver, retorna 0
   Return(result);
 End If;
 -- Verifica se o usu�rio � o cadastrador
 If p_usuario = w_cadastrador Then Result := 1; End If;
 
 -- Verifica se o usu�rio � o executor
 If p_usuario = w_executor Then Result := 1; End If;

 -- Verifica se a solicita��o � do m�dulo de planejamento estrat�gico
 If w_sg_modulo = 'PE' and w_interno = 'S' Then Result := 1; End If;
 
 -- Verifica se o usu�rio � representante de projeto
 select count(*) into w_existe from pj_projeto_representante a where a.sq_pessoa = p_usuario and a.sq_siw_solicitacao = p_solicitacao;
 If w_existe > 0 Then Result := 1; End If;
 
 -- Verifica se o usu�rio � representante de acordo
 select count(*) into w_existe from ac_acordo_representante a where a.sq_pessoa = p_usuario and a.sq_siw_solicitacao = p_solicitacao;
 If w_existe > 0 Then Result := 1; End If;
 
 -- Verifica se o usu�rio � o solicitante
 If w_solicitante = p_usuario Then 
    Result                   := Result + 2; 
    w_unidade_beneficiario   := w_sq_unidade_lotacao;
 Else 
    -- Verifica se o usu�rio participou de alguma forma na solicita��o
    select count(*) into w_existe from (
      -- Verifica se o usu�rio � interessado na demanda
      select 1 from gd_demanda_interes a where a.sq_siw_solicitacao = p_solicitacao and a.sq_pessoa = p_usuario
      UNION
      -- Verifica se j� participou em algum momento na demanda 
      select 1 from gd_demanda_log a where a.sq_siw_solicitacao = p_solicitacao and a.destinatario = p_usuario
      UNION
      -- Verifica se o usu�rio � interessado no projeto
      select 1 from pj_projeto_interes a where a.sq_siw_solicitacao = p_solicitacao and a.sq_pessoa = p_usuario
      UNION
      -- Verifica se o usu�rio � interessado na solicita��o
      select 1 from siw_solicitacao_interessado a where a.sq_siw_solicitacao = p_solicitacao and a.sq_pessoa = p_usuario
      UNION
      -- Verifica se j� participou em algum momento no projeto
      select 1 from pj_projeto_log a where a.sq_siw_solicitacao = p_solicitacao and a.destinatario = p_usuario
      UNION
      -- Verifica se � outra parte no acordo
      select 1 from ac_acordo a where a.sq_siw_solicitacao = p_solicitacao and a.outra_parte= p_usuario
      UNION
      -- Verifica se � outra parte no acordo
      select 1 from ac_acordo_outra_parte a where a.sq_siw_solicitacao = p_solicitacao and a.outra_parte = p_usuario
      UNION
      -- Verifica se � benefici�rio de algum lan�amento financeiro
      select 1 from fn_lancamento a where a.sq_siw_solicitacao = p_solicitacao and a.pessoa = p_usuario
      UNION
      -- Verifica se � proposto de alguma viagem
      select 1 from pd_missao a where a.sq_siw_solicitacao = p_solicitacao and a.sq_pessoa = p_usuario
    ) a;
    If w_existe > 0 Then
       Result := Result + 2;
    End If;
    
    -- recupera o c�digo e a lota��o do solicitante, para verificar, mais abaixo,
    -- se o usu�rio � chefe dele
    select count(b.sq_pessoa) into w_existe
      from siw_solicitacao            a
           inner join sg_autenticacao b on (a.solicitante = b.sq_pessoa)
     where a.sq_siw_solicitacao = p_solicitacao;

    if w_existe > 0 then
       select a.solicitante, b.sq_unidade
         into w_solicitante, w_unidade_beneficiario
         from siw_solicitacao            a
              inner join sg_autenticacao b on (a.solicitante = b.sq_pessoa)
        where a.sq_siw_solicitacao = p_solicitacao;
    else
       select a.solicitante, b.sq_unidade
         into w_solicitante, w_unidade_beneficiario
         from siw_solicitacao            a
              inner join sg_autenticacao b on (a.cadastrador = b.sq_pessoa)
        where a.sq_siw_solicitacao = p_solicitacao;
    end if;
 End If;
 
 -- Se o servi�o for vinculado � unidade
 If w_vinculacao = 'U' Then
    -- Verifica se o usu�rio est� lotado ou se � titular/substituto 
    -- da unidade de CADASTRAMENTO da solicita��o ou se �
    -- da unidade RESPONS�VEL pelo projeto ou pela demanda
    If w_sq_unidade_lotacao   = w_unidade_solicitante or
       w_sq_unidade_lotacao   = w_unidade_resp Then
       If w_interno = 'S' Then Result := Result + 1; End If;
    Elsif w_sq_pessoa_titular    = p_usuario or
          w_sq_pessoa_substituto = p_usuario
    Then
       If w_interno = 'S' Then Result := Result + 4; End If;
    Else
       -- Verifica se o usu�rio � respons�vel por uma unidade envolvida na execu��o
       select count(*) into w_existe
         from gd_demanda_envolv          a
              inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                               b.sq_pessoa    = p_usuario    and
                                               b.fim          is null
                                              )
        where a.sq_siw_solicitacao = p_solicitacao
          and a.sq_unidade         = w_sq_unidade_lotacao;
       If w_existe > 0 Then 
          Result := Result + 4; 
       Else
          -- Verifica se o usu�rio � respons�vel por uma unidade envolvida na execu��o
          select count(*) into w_existe
            from pj_projeto_envolv          a
                 inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                                  b.sq_pessoa    = p_usuario    and
                                                  b.fim          is null
                                                 )
           where a.sq_siw_solicitacao = p_solicitacao
             and a.sq_unidade         = w_sq_unidade_lotacao;
          If w_existe > 0 Then 
             Result := Result + 4; 
          Else
             -- Verifica se o usu�rio tem vis�o geral no centro de custos ao qual a solicita��o est� vinculada
             select count(*) into w_existe
               from siw_pessoa_cc a
              where a.sq_pessoa = p_usuario
                and a.sq_menu   = w_sq_servico
                and a.sq_cc     = w_sq_cc;
             If w_existe > 0 Then
                If w_interno = 'S' 
                   Then Result := Result + 4;
                   Else Result := Result + 2;
                End If;
             End If;
          End If;
       End If;
    End If;
 -- Caso contr�rio, se o servi�o for vinculado � pessoa
 Elsif w_vinculacao = 'P' Then

    -- Verifica se o usu�rio � respons�vel pela unidade do solicitante
    select count(*) into w_chefe_beneficiario
      from eo_unidade_resp a
     where a.sq_unidade = w_unidade_beneficiario 
       and a.sq_pessoa  = p_usuario
       and a.fim        is null;

    -- Verifica se o usu�rio � o titular ou o substituto da unidade
    -- de lota��o do BENEFICI�RIO da solicita��o, ou se participa em algum tr�mite
    -- do servi�o
    If w_chefe_beneficiario > 0 Then 
       Result := Result + 4; 
    Else
          -- Verifica se o usu�rio � respons�vel por uma unidade envolvida na execu��o
       select count(*) into w_existe
         from gd_demanda_envolv           a
               inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                                b.sq_pessoa    = p_usuario    and
                                                b.fim          is null
                                               )
        where a.sq_siw_solicitacao = p_solicitacao
          and a.sq_unidade         = w_unidade_beneficiario;
       If w_existe > 0 Then 
          Result := Result + 4; 
       Else
          -- Verifica se o usu�rio � respons�vel por uma unidade envolvida na execu��o
          select count(*) into w_existe
             from pj_projeto_envolv          a
                  inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                                   b.sq_pessoa    = p_usuario    and
                                                   b.fim          is null
                                                  )
            where a.sq_siw_solicitacao = p_solicitacao
              and a.sq_unidade         = w_unidade_beneficiario;
          If w_existe > 0 Then 
             Result := Result + 4; 
          Else
             -- Verifica se o usu�rio tem vis�o geral no centro de custos ao qual a solicita��o est� vinculada
             select count(*)
               into w_existe
               from siw_pessoa_cc a
              where a.sq_pessoa = p_usuario
                and a.sq_menu   = w_sq_servico
                and a.sq_cc     = w_sq_cc;
             If w_existe > 0 Then
                If w_interno = 'S' 
                   Then Result := Result + 4;
                   Else Result := Result + 2;
                End If;
             End If;
          End If;
       End If;
    End If;
 End If;
 
 -- Verifica se o usu�rio � gestor do m�dulo � qual a solicita��o pertence
 select count(*)
   into w_existe
   from sg_pessoa_modulo a
  where a.sq_pessoa          = p_usuario
    and a.sq_modulo          = w_modulo
    and (a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(w_unidade_solicitante,0)) or
         a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(w_unidade_beneficiario,0)) or
         a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(w_unidade_resp,0))
        );
 If w_existe > 0 or w_gestor_sistema = 'S' Then
    Result := Result + 6;
    If w_gestor_sistema = 'N' and w_destinatario = 'N' and w_sigla_situacao <> 'CI' Then
       -- Se o tr�mite da solicita��o n�o for cadastramento inicial e se o tr�mite n�o indicar destinatario
       -- e se n�o for gestor do sistema, complementa o resultado para somar 16
       Result := Result + 10;
    End If;
 Else
    -- Verifica se � titular ou substituto de alguma unidade respons�vel por etapa
    select count(*) into w_existe
      from pj_projeto_etapa           a
           inner join eo_unidade_resp b on (a.sq_unidade   = b.sq_unidade and
                                            b.sq_pessoa    = p_usuario    and
                                            b.fim          is null
                                           )
     where a.sq_siw_solicitacao = p_solicitacao
       and a.sq_unidade         = w_unidade_beneficiario;
    If w_existe > 0 Then 
       Result := Result + 8; 
    Else
       -- Verifica se � respons�vel por alguma etapa do projeto ou por alguma quest�o ou por alguma meta
       select count(*) into w_existe from (
         -- Verifica se o usu�rio � respons�vel por alguma meta
         select 1 from siw_solic_meta a where a.sq_siw_solicitacao = p_solicitacao and a.sq_pessoa = p_usuario
         UNION
         -- Verifica se o usu�rio � respons�vel por alguma quest�o
         select 1 from siw_restricao a where a.sq_siw_solicitacao = p_solicitacao and a.sq_pessoa = p_usuario
         UNION
         -- Verifica se o usu�rio � respons�vel por alguma etapa
         select 1 from pj_projeto_etapa a where a.sq_siw_solicitacao = p_solicitacao and a.sq_pessoa = p_usuario
       ) a;
       If w_existe > 0 Then Result := Result + 8; End If;
    End If;
 End If;


 -- Verifica se o usu�rio tem permiss�o para cumprir o tr�mite atual da solicita��o
 -- Uma das possibilidades � o tr�mite ser cumprido pelo titular/substituto
 -- da unidade do cadastrador ou da solicita��o ou usu�rios que tenham permiss�o
 If w_chefia_imediata = 'S' Then
 
    If w_executor = p_usuario Then
       -- Se a solicita��o tem indica��o do executor, verifica se ele � o usu�rio.
       Result := Result + 16;
    Else
       -- Se o tr�mite N�O tem indica��o de destinat�rio, 
       -- verifica se o usu�rio est� entre as pessoas que podem cumpr�-lo
       select count(*) into w_existe 
         from sg_tramite_pessoa a 
        where a.sq_pessoa          = p_usuario
          and a.sq_pessoa_endereco = w_sq_endereco_unidade 
          and a.sq_siw_tramite     = w_sq_siw_tramite
          and (w_sg_modulo <> 'PA' or
               (w_sg_modulo = 'PA' and
                0 < (select count(*) from pa_documento where sq_siw_solicitacao = p_solicitacao and unidade_int_posse = w_sq_unidade_lotacao)
               )
              );
       If w_existe > 0 Then 
          Result := Result + 16; 
       Else
          -- Se o servi�o for vinculado � unidade, testa a unidade que cadastrou a solicita��o.
          -- Caso contr�rio, testa a unidade de lota��o do solicitante.
          If w_vinculacao = 'U' Then
             w_unidade_atual := w_unidade_resp;
          Elsif w_vinculacao = 'P' Then
             w_unidade_atual := w_unidade_beneficiario;
          End If;
     
          loop
             w_existe := 1;
             for crec in c_Unidade (w_unidade_atual) loop
                 -- Se o servi�o for vinculado � pessoa:
                 --   a) se o solicitante n�o for o titular nem o substituto, aparece apenas na mesa do titular e do substituto;
                 --   a) se o solicitante for o substituto, aparece na mesa do titular;
                 --   b) se o solicitante for o titular:
                 --      b.1) se h� uma unidade superior ela deve ser assinada por chefes superiores;
                 --      b.2) se n�o h� uma unidade superior ela deve ser assinada pelo substituto.
                 -- Se o servi�o for vinculado � unidade:
                 --   a) A solicita��o aparece na mesa do titular e do substituto da unidade
                 If crec.sq_pessoa_titular is not null Then
                    If w_vinculacao = 'P' Then
                       If crec.sq_pessoa_titular    <> w_solicitante and 
                          crec.sq_pessoa_substituto <> w_solicitante and 
                          (crec.sq_pessoa_titular   = p_usuario or crec.sq_pessoa_substituto = p_usuario) Then
                          Result   := Result + 16;
                       Elsif crec.sq_pessoa_substituto = w_solicitante and
                             crec.sq_pessoa_titular    = p_usuario Then
                             Result   := Result + 16;
                       Elsif crec.sq_pessoa_titular = w_solicitante and
                             crec.sq_pessoa_titular = p_usuario Then
                          If crec.sq_unidade_pai is not null Then
                             w_unidade_atual := crec.sq_unidade_pai;
                             w_existe        := 0;
                          Else
                             If crec.sq_pessoa_substituto = p_usuario Then
                                Result   := Result + 16;
                             End If;
                          End If;
                       Else
                          If crec.sq_pessoa_titular    = w_solicitante and
                             crec.sq_pessoa_substituto = p_usuario and
                             crec.sq_unidade_pai       is null Then
                                Result   := Result + 16;
                          Else
                             w_unidade_atual := crec.sq_unidade_pai;
                             w_existe        := 0;
                          End If;
                       End If;
                    Elsif w_vinculacao = 'U' Then
                       If crec.sq_pessoa_titular = p_usuario or crec.sq_pessoa_substituto = p_usuario Then
                          Result    := Result + 16;
                       End If;
                    End If;
                 Else
                    If crec.sq_unidade_pai is not null Then
                       w_unidade_atual := crec.sq_unidade_pai;
                       w_existe        := 0;
                    Else
                       If crec.sq_pessoa_titular    = w_solicitante and
                          crec.sq_pessoa_substituto = p_usuario Then
                             Result   := Result + 16;
                       Else
                          -- Entrar aqui significa que n�o foi encontrado nenhum respons�vel cadastrado no sistema,
                          -- o que � um erro. No m�dulo de estrutura organizacional, informar os respons�veis.
                          w_existe           := 1;
                       End If;
                    End If;
                 End If;
             end loop;
            
             If w_existe = 1 Then
                exit;
             End If;
          end loop;
       End If;
    End If;

 -- Outra possibilidade � o tr�mite ser cumprido pelo titular/substituto
 -- da unidade de execu��o
 Elsif w_chefia_imediata = 'U' Then
    If w_executor = p_usuario Then
       -- Se a solicita��o tem indica��o do executor, verifica se ele � o usu�rio.
       Result := Result + 16;
    Else
       -- Verifica se o usu�rio � respons�vel pela unidade executora
       select count(*) into w_existe
         from eo_unidade_resp a
        where a.sq_unidade = w_sq_unidade_executora
          and a.sq_pessoa  = p_usuario
          and a.fim        is null;
       If w_existe > 0 Then 
          Result := Result + 16;
       Else
          select count(*) into w_existe 
            from sg_tramite_pessoa a 
           where a.sq_pessoa          = p_usuario
             and a.sq_pessoa_endereco = w_sq_endereco_unidade 
             and a.sq_siw_tramite     = w_sq_siw_tramite
             and (w_sg_modulo <> 'PA' or
                  (w_sg_modulo = 'PA' and
                   0 < (select count(*) from pa_documento where sq_siw_solicitacao = p_solicitacao and unidade_int_posse = w_sq_unidade_lotacao)
                  )
                 );
          If w_existe > 0 Then Result := Result + 16; End If;
       End If;
    End If;
 Elsif w_chefia_imediata = 'I' Then
    -- Quando o tr�mite for cumprido por todos os usu�rios internos
    If w_interno = 'S' Then
       Result := Result + 16;
    End If;
 Elsif w_sigla_situacao = 'AT' and  w_solicitante = p_Usuario and w_consulta_opiniao = 'S' and w_opiniao_solicitante is null Then
    -- Outra possibilidade � a solicita��o estar conclu�da e pendente de opini�o pelo
    -- solicitante
    Result := Result + 16;
 Else
    -- Outra possibilidade � o tr�mite ser cumprido por uma pessoa que tenha
    -- permiss�o para isso
    select count(*) into w_existe 
      from sg_tramite_pessoa a 
     where a.sq_pessoa          = p_usuario
       and a.sq_pessoa_endereco = w_sq_endereco_unidade 
       and a.sq_siw_tramite     = w_sq_siw_tramite
       and (w_sg_modulo <> 'PA' or
            (w_sg_modulo = 'PA' and
             0 < (select count(*) from pa_documento where sq_siw_solicitacao = p_solicitacao and unidade_int_posse = w_sq_unidade_lotacao)
            )
           );
    If w_existe > 0 and w_destinatario = 'N' Then 
       Result := Result + 16; 
    Else
       -- Outra possibilidade � a solicita��o estar sendo executada pelo usu�rio
       -- Neste caso a solicita��o deve estar em tramite ativo e diferente de cadastramento
       If w_executor = p_usuario and w_ativo = 'S' and w_sigla_situacao <> 'CI' Then 
          Result := Result + 16;
       Elsif w_sg_modulo = 'OR' Then
          -- Se for m�dulo de or�amento, outra possibilidade � a solicita��o ter metas e o usu�rio ser:
          -- respons�vel pelo monitoramento, tit/subst do setor respons�vel pelo monitoramento ou
          -- tit/subst da unidade executora do servi�o.
          If p_usuario = w_solicitante Then
             Result := Result + 16;
          Else
             -- Verifica se o usu�rio � respons�vel pela unidade executora
             select count(*) into w_existe
               from eo_unidade_resp a
              where a.sq_unidade = w_sq_unidade_executora
                and a.sq_pessoa  = p_usuario
                and a.fim        is null;
             If w_existe > 0 Then 
                Result := Result + 16;
             Else
                -- Verifica, nas demandas, se o usu�rio � respons�vel pela unidade respons�vel pelo monitoramento
                select count(*) into w_existe
                  from eo_unidade_resp       a
                       inner join gd_demanda b on (a.sq_unidade = b.sq_unidade_resp)
                 where b.sq_siw_solicitacao = p_solicitacao
                   and a.sq_pessoa          = p_usuario
                   and a.fim                is null;
                If w_existe > 0 Then 
                   Result := Result + 16;
                Else
                   -- Verifica, nas demandas, se o usu�rio � respons�vel pela unidade respons�vel pelo monitoramento
                   select count(*) into w_existe
                     from eo_unidade_resp       a
                          inner join pj_projeto b on (a.sq_unidade = b.sq_unidade_resp)
                    where b.sq_siw_solicitacao = p_solicitacao
                      and a.sq_pessoa          = p_usuario
                      and a.fim                is null;
                   If w_existe > 0 Then 
                      Result := Result + 16;
                   End If;
                End If;
             End If;
          End If;
       End If;
    End If;

 End If;

 return(Result);
end Acesso;
/
