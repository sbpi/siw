create or replace function Acesso_IS
  (p_solicitacao in number,
   p_usuario      in number
  ) return number is
/**********************************************************************************
* Nome      : SolicitacaoAcesso
* Finalidade: Verificar se o usu�rio t�m acesso a uma solicitacao, de acordo com os par�metros informados
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      :  14/10/2003, 10:35
* Par�metros:
*    p_solicitacao : chave prim�ria de SR_SOLICITACAO
*    p_usuario   : chave de acesso do usu�rio
* Retorno: campo do tipo bit
*   16: Se a solicita��o deve aparecer na mesa de trabalho do usu�rio
*    8: Se o usu�rio � gestor do m�dulo � qual a solicita��o pertence
*    4: Se o usu�rio � o respons�vel pela unidade de lota��o do solicitante da solicita��o
*       Obs: somente se o tr�mite for cumprido pela chefia imediata
*       Outra possibilidade � se o usu�rio cumprir algum tr�mite no servi�o
*       Outra possibilidade � se o servi�o for de interesse de toda a unidade e o usu�rio for lotado nela
*    2: Se o usu�rio � o solicitante da solicitacao ou se � um interessado na sua execu��o
*    1: Se o usu�rio � o cadastrador da solicita��o
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
  w_sq_servico             siw.siw_menu.sq_menu%type;
  w_acesso_geral           siw.siw_menu.acesso_geral%type;
  w_modulo                 siw.siw_menu.sq_modulo%type;
  w_sigla                  siw.siw_menu.sigla%type;
  w_username               siw.sg_autenticacao.sq_pessoa%type;
  w_sq_unidade_lotacao     siw.sg_autenticacao.sq_unidade%type;
  w_gestor_seguranca       siw.sg_autenticacao.gestor_seguranca%type;  
  w_gestor_sistema         siw.sg_autenticacao.gestor_sistema%type;
  w_sq_unidade_executora   siw.siw_menu.sq_unid_executora%type;        -- Unidade executora do servi�o
  w_consulta_opiniao       siw.siw_menu.consulta_opiniao%type;
  w_acompanha_fases        siw.siw_menu.acompanha_fases%type;
  w_envia_email            siw.siw_menu.envia_email%type;
  w_exibe_relatorio        siw.siw_menu.exibe_relatorio%type;
  w_vinculacao             siw.siw_menu.vinculacao%type;
  w_sq_siw_tramite         siw.siw_solicitacao.sq_siw_tramite%type;
  w_cadastrador            siw.siw_solicitacao.cadastrador%type;
  w_unidade_solicitante    siw.siw_solicitacao.sq_unidade%type;
  w_sq_pessoa_executor     siw.siw_solicitacao.executor%type;
  w_opiniao_solicitante    siw.siw_solicitacao.opiniao%type;
  w_ordem                  siw.siw_tramite.ordem%type;
  w_sq_cc                  siw.siw_solicitacao.sq_cc%type;
  w_sigla_situacao         siw.siw_tramite.sigla%type;
  w_ativo                  siw.siw_tramite.ativo%type;
  w_usuario_ativo          siw.sg_autenticacao.ativo%type;
  w_chefia_imediata        siw.siw_tramite.chefia_imediata%type;
  w_sq_pessoa_titular      siw.eo_unidade_resp.sq_pessoa%type;         -- Titular da unidade solicitante
  w_sq_pessoa_substituto   siw.eo_unidade_resp.sq_pessoa%type;         -- Substituto da unidade solicitante
  w_sq_endereco_unidade    siw.eo_unidade.sq_pessoa_endereco%type;
  w_solicitante            number(10);                             -- Solicitante
  w_unidade_beneficiario   number(10);
  w_existe                 number(10);
  w_unidade_atual          number(10);
  w_chefe_beneficiario     number(10);
  w_executor               number(10);
  Result                   number := 0;

  cursor c_unidade (p_unidade in number) is
     select pt.sq_unidade, a.sq_unidade_pai, Nvl(pt.nome, -1) sq_pessoa_titular,
            Nvl(ps.nome, -1) sq_pessoa_substituto
      from siw.eo_unidade  a,
           (select b.sq_unidade, a.nome_resumido nome
              from siw.co_pessoa                  a,
                   siw.eo_unidade_resp b 
             where (a.sq_pessoa       = b.sq_pessoa and 
                    b.tipo_respons    = 'T' and 
                    b.fim             is null and 
                    b.sq_unidade      = p_unidade)       
           ) pt, 
           (select b.sq_unidade, nome_resumido nome
              from siw.co_pessoa          a,
                   siw.eo_unidade_resp    b
             where (a.sq_pessoa      = b.sq_pessoa and 
                    b.tipo_respons   = 'S' and 
                    b.fim            is null and 
                    b.sq_unidade     = p_unidade)      
           ) ps 
       where (a.sq_unidade  = pt.sq_unidade (+))
         and (a.sq_unidade  = ps.sq_unidade (+))
         and a.sq_unidade  = p_unidade;
begin

 -- Recupera as informa��es da op��o � qual a solicita��o pertence
 select a.acesso_geral, a.sq_menu, a.sq_modulo, a.sigla,
        b.sq_pessoa, b.sq_unidade, b.gestor_seguranca, b.gestor_sistema, b.ativo usuario_ativo,
        a.sq_unid_executora, a.consulta_opiniao, a.acompanha_fases, a.envia_email, a.exibe_relatorio, a.vinculacao, 
        d.sq_siw_tramite, d.solicitante, d.cadastrador, d.sq_unidade, d.executor, d.opiniao, d.sq_cc,
        e.ordem, e.sigla, e.ativo, e.chefia_imediata,
        Nvl(f.sq_pessoa,-1), Nvl(g.sq_pessoa,-1),
        h.sq_pessoa_endereco, d.executor
   into w_acesso_geral, w_sq_servico, w_modulo, w_sigla, 
        w_username, w_sq_unidade_lotacao, w_gestor_seguranca, w_gestor_sistema, w_usuario_ativo,
        w_sq_unidade_executora, w_consulta_opiniao, w_acompanha_fases, w_envia_email, w_exibe_relatorio, w_vinculacao,
        w_sq_siw_tramite, w_solicitante, w_cadastrador, w_unidade_solicitante, w_sq_pessoa_executor, w_opiniao_solicitante, w_sq_cc,
        w_ordem, w_sigla_situacao, w_ativo, w_chefia_imediata,
        w_sq_pessoa_titular, w_sq_pessoa_substituto,
        w_sq_endereco_unidade, w_executor
   from siw.sg_autenticacao                       b,
        siw.siw_solicitacao                       d,
        siw.siw_menu           a, 
        siw.siw_tramite        e,
        siw.eo_unidade         h,
        siw.eo_unidade_resp    f,
        siw.eo_unidade_resp    g
  where (a.sq_menu                = d.sq_menu)
    and (d.sq_siw_tramite         = e.sq_siw_tramite)
    and (d.sq_unidade             = h.sq_unidade)
    and (d.sq_unidade             = f.sq_unidade (+) and
         f.tipo_respons (+)       = 'T'          and
         f.fim (+)                is null)
    and (d.sq_unidade             = g.sq_unidade (+) and
         g.tipo_respons (+)       = 'S'          and
         g.fim (+)                is null)
    and d.sq_siw_solicitacao     = p_solicitacao
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

 -- Verifica se o usu�rio � o solicitante
 If w_solicitante = p_usuario Then 
    Result                   := Result + 2; 
    w_unidade_beneficiario   := w_sq_unidade_lotacao;
 Else 
    -- Verifica se o usu�rio � interessado na demanda ou se j� participou em algum momento
    select count(*) into w_existe
      from siw.gd_demanda_interes a
     where a.sq_siw_solicitacao = p_solicitacao
       and a.sq_pessoa          = p_usuario;
    If w_existe > 0 Then 
      Result := + 2; 
    Else
      select count(*) into w_existe
        from siw.gd_demanda_log a
       where a.sq_siw_solicitacao = p_solicitacao
         and a.destinatario      = p_usuario;
       If w_existe > 0 Then 
          Result := + 2; 
       Else
          -- Verifica se o usu�rio � interessado no projeto ou se j� participou em algum momento
          select count(*) into w_existe
            from siw.pj_projeto_interes a
           where a.sq_siw_solicitacao = p_solicitacao
             and a.sq_pessoa          = p_usuario;
          If w_existe > 0 Then 
            Result := + 2; 
          Else
            select count(*) into w_existe
              from siw.pj_projeto_log a
             where a.sq_siw_solicitacao = p_solicitacao
               and a.destinatario      = p_usuario;
             If w_existe > 0 Then 
                Result := + 2; 
             Else
                -- Verifica se � respons�vel por uma etapa de projeto
                select count(*) into w_existe
                  from siw.pj_projeto_etapa a
                 where a.sq_siw_solicitacao = p_solicitacao
                   and a.sq_pessoa          = p_usuario;
                If w_existe > 0 Then
                   Result := + 2;
                End If;
             End If;
          End If;
       End If;
    End If;
    
    -- recupera o c�digo e a lota��o do solicitante, para verificar, mais abaixo,
    -- se o usu�rio � chefe dele
    select a.solicitante, b.sq_unidade
      into w_solicitante, w_unidade_beneficiario
      from siw.siw_solicitacao a,
           siw.sg_autenticacao b 
     where (a.solicitante = b.sq_pessoa)
       and a.sq_siw_solicitacao = p_solicitacao;
 End If;
 
 -- Se o servi�o for vinculado � unidade
 If w_vinculacao = 'U' Then
    -- Verifica se o usu�rio est� lotado ou se � titular/substituto 
    -- da unidade de CADASTRAMENTO da solicita��o
    If w_sq_unidade_lotacao   = w_unidade_solicitante or
       w_sq_pessoa_titular    = p_usuario             or
       w_sq_pessoa_substituto = p_usuario
    Then
       Result := Result + 4; 
    Else
       -- Verifica se participa em algum tr�mite do servi�o
       select count(*) 
         into w_existe
         from siw.sg_tramite_pessoa        a,
              siw.siw_tramite c,
              siw.siw_menu    b
        where (a.sq_siw_tramite      = c.sq_siw_tramite)
          and (b.sq_menu             = c.sq_menu)
          and Nvl(c.sigla,'---')    <> 'CI'
          and b.sq_menu             = w_sq_servico
          and a.sq_pessoa           = p_usuario;
       If w_existe > 0 Then
          Result := Result + 4;
       End If;
    End If;
 -- Caso contr�rio, se o servi�o for vinculado � pessoa
 Elsif w_vinculacao = 'P' Then

    -- Verifica se o usu�rio � respons�vel pela unidade do solicitante
    select count(*) into w_chefe_beneficiario
      from siw.eo_unidade_resp a
     where a.sq_unidade = w_unidade_beneficiario 
       and a.sq_pessoa  = p_usuario
       and a.fim        is null;

    -- Verifica se o usu�rio � o titular ou o substituto da unidade
    -- de lota��o do BENEFICI�RIO da solicita��o, ou se participa em algum tr�mite
    -- do servi�o
    If w_chefe_beneficiario > 0 Then 
       Result := Result + 4; 
    Else
       -- Verifica se participa em algum tr�mite do servi�o
       select count(*) 
         into w_existe
         from siw.sg_tramite_pessoa a, siw.siw_menu b, siw.siw_tramite c
        where b.sq_menu             = c.sq_menu   
          and a.sq_siw_tramite      = c.sq_siw_tramite
          and Nvl(c.sigla,'---')    <> 'CI'
          and b.sq_menu             = w_sq_servico
          and a.sq_pessoa           = p_usuario;
       If w_existe > 0 Then
          Result := Result + 4;
       End If;
    End If;
 End If;
 
 -- Verifica se o usu�rio � gestor do sistema ou do m�dulo � qual a solicita��o pertence
 select count(*)
   into w_existe
   from siw.sg_pessoa_modulo a
  where a.sq_pessoa = p_usuario
    and a.sq_modulo = w_modulo;
 If w_existe > 0 or w_gestor_sistema = 'S' Then
    Result := Result + 8;
 End If;


 -- Verifica se o usu�rio tem permiss�o para cumprir o tr�mite atual da solicita��o
 -- Uma das possibilidades � o tr�mite ser cumprido pelo titular/substituto
 -- da unidade do cadastrador ou da solicita��o
 If w_chefia_imediata = 'S' Then
 
    -- Se o servi�o for vinculado � unidade, testa a unidade que cadastrou a solicita��o.
    -- Caso contr�rio, testa a unidade de lota��o do solicitante.
    If w_vinculacao = 'U' Then
       w_unidade_atual := w_unidade_solicitante;
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
                    w_unidade_atual := crec.sq_unidade_pai;
                    w_existe        := 0;
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
                 -- Entrar aqui significa que n�o foi encontrado nenhum respons�vel cadastrado no sistema,
                 -- o que � um erro. No m�dulo de estrutura organizacional, informar os respons�veis.
                 w_existe           := 1;
              End If;
           End If;
       end loop;
       
       If w_existe = 1 Then
          exit;
       End If;
    end loop;

 -- Outra possibilidade � o tr�mite ser cumprido pelo titular/substituto
 -- da unidade de execu��o
 Elsif w_chefia_imediata = 'U' Then
    -- Verifica se o usu�rio � respons�vel pela unidade executora
    select count(*) into w_existe
      from siw.eo_unidade_resp a
     where a.sq_unidade = w_sq_unidade_executora
       and a.sq_pessoa  = p_usuario
       and a.fim        is null;
    If w_existe > 0 Then 
       Result := Result + 16;
    Else
       select count(*) into w_existe 
         from siw.sg_tramite_pessoa a 
        where a.sq_pessoa          = p_usuario
          and a.sq_pessoa_endereco = w_sq_endereco_unidade 
          and a.sq_siw_tramite     = w_sq_siw_tramite;
       If w_existe > 0 Then Result := Result + 16; End If;
    End If;
 -- Outra possibilidade � a solicita��o estar conclu�da e pendente de opini�o pelo
 -- solicitante
 Elsif w_sigla_situacao = 'AT' and  w_solicitante = p_Usuario and w_consulta_opiniao = 'S' and w_opiniao_solicitante is null Then
    Result := Result + 16;
 Else
    -- Outra possibilidade � o tr�mite ser cumprido por uma pessoa que tenha
    -- permiss�o para isso
    select count(*) into w_existe 
      from siw.sg_tramite_pessoa a 
     where a.sq_pessoa          = p_usuario
       and a.sq_pessoa_endereco = w_sq_endereco_unidade 
       and a.sq_siw_tramite     = w_sq_siw_tramite;
    If w_existe > 0 Then 
       Result := Result + 16; 
    Else
       -- Outra possibilidade � a solicita��o estar sendo executada pelo usu�rio
       If w_executor = p_usuario Then 
          Result := Result + 16;
       Else
          -- Se for m�dulo de or�amento, outra possibilidade � a solicita��o ter metas e o usu�rio ser:
          -- respons�vel pelo monitoramento, tit/subst do setor respons�vel pelo monitoramento ou
          -- tit/subst da unidade executora do servi�o.
          If p_usuario = w_solicitante Then
             Result := Result + 16;
          Else
             -- Verifica se o usu�rio � respons�vel pela unidade executora
             select count(*) into w_existe
               from siw.eo_unidade_resp a
              where a.sq_unidade = w_sq_unidade_executora
                and a.sq_pessoa  = p_usuario
                and a.fim        is null;
             If w_existe > 0 Then 
                Result := Result + 16;
             Else
                -- Verifica, nas demandas, se o usu�rio � respons�vel pela unidade respons�vel pelo monitoramento
                select count(*) into w_existe
                  from siw.eo_unidade_resp       a,
                       siw.gd_demanda b 
                 where (a.sq_unidade = b.sq_unidade_resp)
                   and b.sq_siw_solicitacao = p_solicitacao
                   and a.sq_pessoa          = p_usuario
                   and a.fim                is null;
                If w_existe > 0 Then 
                   Result := Result + 16;
                Else
                   -- Verifica, nas demandas, se o usu�rio � respons�vel pela unidade respons�vel pelo monitoramento
                   select count(*) into w_existe
                     from siw.eo_unidade_resp       a,
                          siw.pj_projeto b
                    where (a.sq_unidade = b.sq_unidade_resp)
                      and b.sq_siw_solicitacao = p_solicitacao
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
end Acesso_IS;
/
