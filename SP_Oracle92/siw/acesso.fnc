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
  w_cliente                siw_cliente.sq_pessoa%type;
  w_interno                co_tipo_vinculo.interno%type;
  w_sq_servico             siw_menu.sq_menu%type;
  w_acesso_geral           siw_menu.acesso_geral%type;
  w_consulta_geral         siw_menu.consulta_geral%type;
  w_modulo                 siw_menu.sq_modulo%type;
  w_sg_modulo              siw_modulo.sigla%type;
  w_sigla                  siw_menu.sigla%type;
  w_destinatario           siw_menu.destinatario%type;
  w_username               sg_autenticacao.sq_pessoa%type;
  w_unidade_lotacao        sg_autenticacao.sq_unidade%type;
  w_gestor_seguranca       sg_autenticacao.gestor_seguranca%type;  
  w_gestor_sistema         sg_autenticacao.gestor_sistema%type;
  w_gestor_financeiro      varchar2(1);
  w_sq_unidade_executora   siw_menu.sq_unid_executora%type;        -- Unidade executora do servi�o
  w_consulta_opiniao       siw_menu.consulta_opiniao%type;
  w_envia_email            siw_menu.envia_email%type;
  w_exibe_relatorio        siw_menu.exibe_relatorio%type;
  w_vinculacao             siw_menu.vinculacao%type;
  w_sq_siw_tramite         siw_solicitacao.sq_siw_tramite%type;
  w_cadastrador            siw_solicitacao.cadastrador%type;
  w_unidade_solicitante    siw_solicitacao.sq_unidade%type;
  w_unidade_acesso         varchar2(1);
  w_solic_pai              siw_solicitacao.sq_solic_pai%type;
  w_executor               siw_solicitacao.executor%type;
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
  w_nm_vinculo             co_tipo_vinculo.nome%type;
  w_solicitante            number(18);                             -- Solicitante
  w_unidade_beneficiario   number(18);
  w_existe                 number(18);
  w_sair                   number(18);
  w_unidade_atual          number(18);
  w_chefe_beneficiario     number(18);
  Result                   number := 0;
  w_unidade_resp           number(18);
  w_anterior               number(18);
  w_beneficiario           number(18);
  w_anterior_assina        varchar2(1);
  w_beneficiario_assina    varchar2(1);
  w_gestor_cumpre          varchar2(1);

  cursor c_unidade (p_unidade in number) is
     select pt.sq_unidade, a.sq_unidade_pai, coalesce(pt.sq_pessoa, -1) as sq_pessoa_titular,
            coalesce(ps.sq_pessoa, -1) as sq_pessoa_substituto
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
 select a.sq_pessoa, a.acesso_geral, a.consulta_geral, a.sq_menu, a.sq_modulo, a.sigla, e.destinatario,
        a1.sigla,
        b.sq_pessoa, b.sq_unidade, b.gestor_seguranca, b.gestor_sistema, b.ativo as usuario_ativo,
        b2.nome, b2.interno,
        a.sq_unid_executora, a.consulta_opiniao, a.envia_email, a.exibe_relatorio, a.vinculacao, 
        d.sq_siw_tramite, d.cadastrador, d.solicitante, d.sq_unidade, d.executor, d.opiniao, d.sq_solic_pai,
        e.ordem, e.sigla, e.ativo, e.chefia_imediata, e.assina_tramite_anterior, e.beneficiario_cumpre, e.gestor_cumpre,
        h.sq_pessoa_endereco, case when i.sq_pessoa is null then 'N' else 'S' end
   into w_cliente, w_acesso_geral, w_consulta_geral, w_sq_servico, w_modulo, w_sigla, w_destinatario,
        w_sg_modulo,
        w_username, w_unidade_lotacao, w_gestor_seguranca, w_gestor_sistema, w_usuario_ativo,
        w_nm_vinculo, w_interno,
        w_sq_unidade_executora, w_consulta_opiniao, w_envia_email, w_exibe_relatorio, w_vinculacao,
        w_sq_siw_tramite, w_cadastrador, w_solicitante, w_unidade_solicitante, w_executor, 
        w_opiniao_solicitante, w_solic_pai,
        w_ordem, w_sigla_situacao, w_ativo, w_chefia_imediata, w_anterior_assina, w_beneficiario_assina, w_gestor_cumpre,
        w_sq_endereco_unidade, w_unidade_acesso
   from sg_autenticacao                     b
        inner   join co_pessoa              b1 on (b.sq_pessoa              = b1.sq_pessoa)
          inner join co_tipo_vinculo        b2 on (b1.sq_tipo_vinculo       = b2.sq_tipo_vinculo),
        siw_solicitacao                     d
        inner   join siw_menu               a  on (a.sq_menu                = d.sq_menu)
          inner join siw_modulo             a1 on (a.sq_modulo              = a1.sq_modulo)
        inner   join siw_tramite            e  on (e.sq_siw_tramite         = coalesce(p_tramite, d.sq_siw_tramite))
        inner   join eo_unidade             h  on (d.sq_unidade             = h.sq_unidade)
          left  join sg_pessoa_unidade      i  on (h.sq_unidade             = i.sq_unidade and i.sq_pessoa = p_usuario)
  where d.sq_siw_solicitacao     = p_solicitacao
    and b.sq_pessoa              = p_usuario;

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

 -- Verifica se a solicita��o � de consulta geral
 If w_consulta_geral = 'S' and w_interno = 'S' Then Result := 1; End If;
 
 -- Se for servi�o de protocolo, verifica se o usu�rio � de uma das unidades de posse
 If w_sigla = 'PADCAD' Then
    select count(*) into w_existe
      from pa_documento a
     where a.sq_siw_solicitacao = p_solicitacao
       and a.unidade_int_posse in (select w_unidade_lotacao from dual
                                   UNION
                                   select sq_unidade_lotacao from gp_contrato_colaborador where sq_pessoa = p_usuario and fim is null
                                   UNION
                                   select sq_unidade_exercicio from gp_contrato_colaborador where sq_pessoa = p_usuario and fim is null
                                   UNION 
                                   select sq_unidade from eo_unidade_resp where sq_pessoa = p_usuario and fim is null
                                   UNION
                                   select sq_unidade from sg_pessoa_unidade where sq_pessoa = p_usuario
                                   UNION
                                   select distinct x.sq_unidade 
                                     from eo_unidade x
                                          inner join sg_pessoa_modulo y on (x.sq_pessoa = y.cliente)
                                          inner join siw_modulo       z on (y.sq_modulo = z.sq_modulo)
                                     where y.sq_pessoa = p_usuario
                                       and z.sigla     = 'PA'
                                  );
    If w_existe > 0 Then
       Return 10;
    Elsif w_consulta_geral = 'S' Then
       Return 1;
    End If;
 End If;
 -- Se usu�rio � do tipo de v�nculo ABDI ou SECRETARIA EXECUTIVA e o ambiente for PDP, concede acesso de consulta
 If w_cliente in (14014,11134) and upper(w_nm_vinculo) in ('ABDI','SECRETARIA EXECUTIVA') Then
    Result := 1;
 End If;
  
 -- Verifica se o usu�rio � representante de projeto
 select count(*) into w_existe from pj_projeto_representante a where a.sq_pessoa = p_usuario and a.sq_siw_solicitacao = p_solicitacao;
 If w_existe > 0 Then Result := 1; End If;
 
 -- Verifica se o usu�rio � coordenador de macroprograma da pdp
 If w_solic_pai is not null Then
   select count(*) into w_existe 
     from siw_solicitacao a
          inner   join siw_menu                    d on (a.sq_menu             = d.sq_menu             and d.sigla = 'PEPROCAD')
          inner   join siw_solicitacao_interessado b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao  and b.sq_pessoa = p_usuario)
            inner join siw_tipo_interessado        c on (b.sq_tipo_interessado = c.sq_tipo_interessado and c.sigla = 'MPGCO')
    where a.sq_siw_solicitacao = w_solic_pai;
   If w_existe > 0 Then Result := 1; End If;
 End If;
 
 -- Verifica se o usu�rio � gestor do m�dulo financeiro e, 
 -- se a solicitacao for do m�dulo de contratos, projetos ou passagens
 select count(*)
   into w_existe
   from sg_pessoa_modulo a
  where a.sq_pessoa           = p_usuario
    and w_modulo              in (select sq_modulo from siw_modulo where sigla in ('AC','PR','PD')) 
    and a.sq_modulo           = (select sq_modulo from siw_modulo where sigla = 'FN')
    and w_sg_modulo           = 'FN'
    and (a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(w_unidade_solicitante,0)) or
         a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(w_unidade_beneficiario,0)) or
         a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(w_unidade_resp,0))
        );
 If w_existe > 0 Then
    Result := 1;
 End If;
 
 -- Verifica se o usu�rio � o solicitante
 If w_solicitante = p_usuario Then 
    Result                   := Result + 2; 
    select sq_unidade into w_unidade_beneficiario from sg_autenticacao where sq_pessoa = p_usuario;
 Else 
    -- Verifica se o usu�rio participou de alguma forma na solicita��o
    select count(*) into w_existe from (
      -- Verifica se o usu�rio participou da tramita��o da solicita��o
      select 1 from siw_solic_log a where a.sq_siw_solicitacao = p_solicitacao and a.sq_pessoa = p_usuario
      UNION
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
      -- Verifica se j� participou em algum momento no programa
      select 1 from pe_programa_log a where a.sq_siw_solicitacao = p_solicitacao and a.cadastrador = p_usuario
      UNION
      -- Verifica se j� participou em algum momento no projeto
      select 1 from pj_projeto_log a where a.sq_siw_solicitacao = p_solicitacao and a.cadastrador = p_usuario
      UNION
      -- Verifica se j� participou em algum momento no projeto
      select 1 from pj_projeto_log a where a.sq_siw_solicitacao = p_solicitacao and a.destinatario = p_usuario
      UNION
      -- Verifica se � outra parte no acordo
      select 1 from ac_acordo a where a.sq_siw_solicitacao = p_solicitacao and a.outra_parte= p_usuario
      UNION
      -- Verifica se o usu�rio participou da tramita��o do acordo
      select 1 from ac_acordo_log a where a.sq_siw_solicitacao = p_solicitacao and a.cadastrador = p_usuario
      UNION
      -- Verifica se � outra parte no acordo
      select 1 from ac_acordo_outra_parte a where a.sq_siw_solicitacao = p_solicitacao and a.outra_parte = p_usuario
      UNION
      -- Verifica se � benefici�rio de algum lan�amento financeiro
      select 1 from fn_lancamento a where a.sq_siw_solicitacao = p_solicitacao and a.pessoa = p_usuario
      UNION
      -- Verifica se j� participou da tramita��o do lan�amento financeiro
      select 1 from fn_lancamento_log a where a.sq_siw_solicitacao = p_solicitacao and a.cadastrador = p_usuario
      UNION
      -- Verifica se � proposto de alguma viagem
      select 1 from pd_missao a where a.sq_siw_solicitacao = p_solicitacao and a.sq_pessoa = p_usuario
    ) a;
    If w_existe > 0 Then
       Result := Result + 2;
    End If;
    
    if w_sigla = 'PDINICIAL' then
       -- Se viagem, tratamento diferenciado para benefici�rios internos e externos � organiza��o
       select a.sq_pessoa,   case c.contratado 
                                  when 'S' then coalesce(a2.sq_unidade_exercicio, a1.sq_unidade, d.sq_unidade)
                                  else e.sq_unidade_resp 
                             end
         into w_solicitante, w_unidade_beneficiario
         from pd_missao                             a
              left    join sg_autenticacao         a1 on (a.sq_pessoa          = a1.sq_pessoa)
              left    join gp_contrato_colaborador a2 on (a.sq_pessoa          = a2.sq_pessoa and a2.fim is null)
              inner   join co_pessoa                b on (a.sq_pessoa          = b.sq_pessoa)
                inner join co_tipo_vinculo          c on (b.sq_tipo_vinculo    = c.sq_tipo_vinculo)
              inner   join siw_solicitacao          d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
              inner   join gd_demanda               e on (a.sq_siw_solicitacao = e.sq_siw_solicitacao)
        where a.sq_siw_solicitacao = p_solicitacao;
    else
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
    end if;
 End If;
 
 -- Verifica se o usu�rio � gestor do m�dulo � qual a solicita��o pertence
 select count(*)
   into w_existe
   from sg_pessoa_modulo a
  where a.sq_pessoa           = p_usuario
    and a.sq_modulo           = w_modulo
    and (a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(w_unidade_solicitante,0)) or
         a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(w_unidade_beneficiario,0)) or
         a.sq_pessoa_endereco = (select sq_pessoa_endereco from eo_unidade where sq_unidade = coalesce(w_unidade_resp,0))
        );
    
 If w_existe > 0 or w_gestor_sistema = 'S' Then
    Result := Result + 6;
    If w_ativo = 'S' and w_existe > 0 and w_gestor_cumpre = 'S' and w_destinatario = 'N' and w_sigla_situacao not in ('CI','PP') Then
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

 -- Recupera as informa��es da op��o � qual a solicita��o pertence
 select coalesce(o.sq_pessoa, d.solicitante), 
        case when d.sq_cc is not null 
             then d.sq_cc
             else case when i.sq_cc is not null
                       then i.sq_cc
                       else j.sq_cc

                  end
        end as sq_cc,
        coalesce(f.sq_pessoa,-1), coalesce(g.sq_pessoa,-1),  
        coalesce(k.sq_unidade_resp, l.sq_unidade_resp,m.sq_unidade_resp,n.sq_unidade,d.sq_unidade) --d.sq_unidade deve sempre ser a �ltima op��o
   into w_solicitante, w_sq_cc, w_sq_pessoa_titular, w_sq_pessoa_substituto, w_unidade_resp
   from siw_solicitacao                     d
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
        left    join gd_demanda             l  on (d.sq_siw_solicitacao     = l.sq_siw_solicitacao)
        left    join pe_programa            m  on (d.sq_siw_solicitacao     = m.sq_siw_solicitacao)
        left    join cl_solicitacao         n  on (d.sq_siw_solicitacao     = n.sq_siw_solicitacao)
        left    join pd_missao              o  on (d.sq_siw_solicitacao     = o.sq_siw_solicitacao)
  where d.sq_siw_solicitacao     = p_solicitacao;

 -- Se o servi�o for vinculado � unidade
 If w_vinculacao = 'U' Then
    -- Verifica se o usu�rio est� lotado ou se � titular/substituto 
    -- da unidade de CADASTRAMENTO da solicita��o 
    -- ou se � da unidade RESPONS�VEL e o m�dulo for de protocolo
    If w_interno = 'S' and w_sigla = 'PADCAD' Then 
       select count(*) into w_existe from pa_documento where sq_siw_solicitacao = p_solicitacao and unidade_int_posse = w_unidade_lotacao;
       If w_existe > 0 Then Result := Result + 1; End If;
    Elsif w_unidade_lotacao   = w_unidade_solicitante or
       w_unidade_lotacao      = w_unidade_resp or
       w_unidade_acesso       = 'S' Then
       Result := Result + 1;
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
          and a.sq_unidade         = w_unidade_lotacao;
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
             and a.sq_unidade         = w_unidade_lotacao;
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
             Else
                -- Verifica se o usu�rio � titular ou substituto de alguma unidade � qual a solicita��o � vinculada
                select count(*) into w_existe
                  from eo_unidade_resp a
                 where a.sq_pessoa = p_usuario
                   and a.fim       is null
                   and sq_unidade in (select w.sq_unidade
                                        from eo_unidade w
                                      connect by prior w.sq_unidade_pai = w.sq_unidade
                                      start with w.sq_unidade = w_unidade_solicitante
                                      UNION
                                      select w.sq_unidade
                                        from eo_unidade w
                                       where w_unidade_solicitante <> w_unidade_resp
                                      connect by prior w.sq_unidade_pai = w.sq_unidade
                                      start with w.sq_unidade = w_unidade_resp
                                     );
                If w_existe > 0 Then 
                   Result := Result + 1;
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
 
 -- Se o tr�mite atual n�o puder ser cumprido pela mesma pessoa que cumpriu o tr�mite anterior, identifica quem cumpriu o tr�mite anterior
 If w_anterior_assina = 'N' Then
   select c.sq_pessoa into w_anterior
     from siw_solicitacao                   a
          inner   join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                          from siw_solic_log              x
                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                         where y.sq_menu   = w_sq_servico
                           and x.devolucao = 'N'
                        group by x.sq_siw_solicitacao
                       )                    b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            inner join siw_solic_log        c on (b.chave              = c.sq_siw_solic_log)
    where a.sq_siw_solicitacao = p_solicitacao;
 Else
   w_anterior := 0;
 End If;
 
 -- Se o tr�mite atual n�o puder ser cumprido pelo benefici�rio da solicita��o, identifica o benefici�rio
 If w_beneficiario_assina = 'N' Then
   w_beneficiario := w_solicitante;
 Else
   w_beneficiario := 0;
 End If;
 
 -- Solicita��o de celular na fase de ci�ncia pelo benefici�rio deve ser cumprida pelo benefici�rio
 if w_sigla_situacao in ('CB','TR') and w_sigla = 'SRSOLCEL' and w_solicitante = p_usuario Then
    Result := Result + 16;
 Elsif w_sigla_situacao = 'PP' Then
    -- Tr�mites com a sigla PP indicam cumprimento vinculado a outro servi�o.
    -- Ex: Pgto di�ria � liberado quando a viagem tem a presta��o de contas registrada.
    null;
 Elsif w_anterior <> p_usuario and w_beneficiario <> p_usuario Then 
   -- A condi��o abaixo verifica se:
   -- 1) o tr�mite atual pode ser cumprido pelo cumpridor do tr�mite anterior
   -- 2) o tr�mite atual pode ser cumprido pelo benefici�rio da solicita��o

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
            --and a.sq_pessoa_endereco = w_sq_endereco_unidade 
            and a.sq_siw_tramite     = w_sq_siw_tramite
            and (w_sigla <> 'PADCAD' or
                 (w_sigla = 'PADCAD' and
                  0 < (select count(*) from pa_documento where sq_siw_solicitacao = p_solicitacao and unidade_int_posse = w_unidade_lotacao)
                 )
                );
         If w_existe > 0 Then 
            Result := Result + 16; 
         Else
            -- Se o servi�o for vinculado � unidade, testa a unidade que cadastrou a solicita��o.
            -- Caso contr�rio, testa a unidade de lota��o do solicitante.
            If w_vinculacao = 'U' Then
               w_unidade_atual := w_unidade_solicitante;
            Elsif w_vinculacao = 'P' Then
               w_unidade_atual := w_unidade_beneficiario;
            End If;
       
            loop
               w_existe := 1;
               w_sair   := 1; -- Vari�vel que controla a sa�da do la�o quando o primeiro chefe � identificado
               for crec in c_Unidade (w_unidade_atual) loop
                   -- Se o servi�o for vinculado � pessoa:
                   --   a) se o solicitante n�o for o titular nem o substituto, aparece apenas na mesa do titular e do substituto;
                   --   a) se o solicitante for o substituto, aparece na mesa do titular;
                   --   b) se o solicitante for o titular:
                   --      b.1) se h� uma unidade superior ela deve ser assinada por chefes superiores;
                   --      b.2) se n�o h� uma unidade superior ela deve ser assinada pelo substituto.
                   -- Se o servi�o for vinculado � unidade:
                   --   a) A solicita��o aparece na mesa do titular e do substituto da unidade
                   If crec.sq_pessoa_titular > 0 Then
                      If w_vinculacao = 'P' Then
                         If (crec.sq_pessoa_titular    <> w_solicitante or
                             (crec.sq_pessoa_titular    = w_solicitante and
                              w_solicitante             <> w_cadastrador
                             )                             
                            ) and 
                            crec.sq_pessoa_substituto <> w_solicitante and 
                            (crec.sq_pessoa_titular   = p_usuario or crec.sq_pessoa_substituto = p_usuario) Then
                            Result   := Result + 16;
                            w_sair   := 1;
                         Elsif crec.sq_pessoa_substituto = w_solicitante and
                               crec.sq_pessoa_titular    = p_usuario Then
                               Result   := Result + 16;
                               w_sair   := 1;
                         Elsif crec.sq_pessoa_titular = w_solicitante and
                               crec.sq_pessoa_titular = p_usuario Then
                            -- Alterado para testes
                            Result   := Result + 16;
                            w_sair   := 1;
                            /*
                            Bloco comentado pois solicita��es ligadas ao usu�rio s� podem ser autorizadas pelo chefe imediato. Os chefes de unidades acima
                            da unidade de lota��o n�o s�o considerados.
                            If crec.sq_unidade_pai is not null Then
                               w_unidade_atual := crec.sq_unidade_pai;
                               w_existe        := 0;
                               w_sair          := 0; -- O chefe da unidade superior assina somente quando o solicitante for titular da unidade
                            Else
                               If crec.sq_pessoa_substituto = p_usuario Then
                                  Result   := Result + 16;
                                  w_sair   := 1;
                               End If;
                            End If;
                            */
                         Else
                            If crec.sq_pessoa_titular    = w_solicitante and
                               crec.sq_pessoa_substituto = p_usuario and
                               crec.sq_unidade_pai       is null Then
                                  Result   := Result + 16;
                                  w_sair   := 1;
                            Else
                               w_unidade_atual := crec.sq_unidade_pai;
                               w_existe        := 0;
                            End If;
                         End If;
                      Elsif w_vinculacao = 'U' Then
                         If crec.sq_pessoa_titular = p_usuario or crec.sq_pessoa_substituto = p_usuario Then
                            Result    := Result + 16;
                            w_sair    := 1;
                         End If;
                      End If;
                   Else
                      If crec.sq_unidade_pai is not null Then
                         w_unidade_atual := crec.sq_unidade_pai;
                         w_existe        := 0;
                         w_sair          := 0;
                      Else
                         If crec.sq_pessoa_titular    = w_solicitante and
                            crec.sq_pessoa_substituto = p_usuario Then
                               Result   := Result + 16;
                               w_sair   := 1;
                         Else
                            -- Entrar aqui significa que n�o foi encontrado nenhum respons�vel cadastrado no sistema,
                            -- o que � um erro. No m�dulo de estrutura organizacional, informar os respons�veis.
                            w_existe           := 1;
                         End If;
                      End If;
                   End If;
               end loop;
              
               If w_existe = 1 or w_sair = 1 Then
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
               --and a.sq_pessoa_endereco = w_sq_endereco_unidade 
               and a.sq_siw_tramite     = w_sq_siw_tramite
               -- Se o usu�rio cumprir o tr�mite, a solicita��o deve ser de uma unidade vinculada � sua lota��o ou o usu�rio deve ter vis�o na unidade solicitante
               and (w_unidade_lotacao   in (select w.sq_unidade
                                              from eo_unidade w
                                            connect by prior w.sq_unidade_pai = w.sq_unidade
                                            start with w.sq_unidade = w_unidade_solicitante
                                           )
                    or
                    w_unidade_solicitante in (select sq_unidade from sg_pessoa_unidade where sq_pessoa = p_usuario)
                   )
               and (w_sigla <> 'PADCAD' or
                    (w_sigla = 'PADCAD' and
                     0 < (select count(*) from pa_documento where sq_siw_solicitacao = p_solicitacao and unidade_int_posse = w_unidade_lotacao)
                    )
                   );
            If w_existe > 0 Then Result := Result + 16; End If;
         End If;
      End If;
   Elsif w_chefia_imediata = 'I' Then
      -- Quando o tr�mite for cumprido por todos os usu�rios internos
      If w_interno = 'S' and w_ativo = 'S' and w_sigla_situacao <> 'CI' Then
         If w_sigla = 'PDINICIAL' Then
           select count(*) into w_existe
             from siw_solicitacao      a
                  inner join pd_missao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            where a.sq_siw_solicitacao = p_solicitacao
              and (a.solicitante       = p_usuario or a.cadastrador = p_usuario or b.sq_pessoa = p_usuario);
           If w_existe > 0 Then
             Result := Result + 16;
           End If;
         Else
           Result := Result + 16;
         End If;
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
         --and a.sq_pessoa_endereco = w_sq_endereco_unidade 
         and a.sq_siw_tramite     = coalesce(p_tramite, w_sq_siw_tramite)
         and (w_sigla <> 'PADCAD' or
              (w_sigla = 'PADCAD' and
               0 < (select count(*) from pa_documento where sq_siw_solicitacao = p_solicitacao and unidade_int_posse = w_unidade_lotacao)
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
 End If;
 return(Result);
end Acesso;
/
