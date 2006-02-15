create or replace function Acesso_IS
  (p_solicitacao in number,
   p_usuario      in number
  ) return number is
/**********************************************************************************
* Nome      : SolicitacaoAcesso
* Finalidade: Verificar se o usuário têm acesso a uma solicitacao, de acordo com os parâmetros informados
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      :  14/10/2003, 10:35
* Parâmetros:
*    p_solicitacao : chave primária de SR_SOLICITACAO
*    p_usuario   : chave de acesso do usuário
* Retorno: campo do tipo bit
*   16: Se a solicitação deve aparecer na mesa de trabalho do usuário
*    8: Se o usuário é gestor do módulo à qual a solicitação pertence
*    4: Se o usuário é o responsável pela unidade de lotação do solicitante da solicitação
*       Obs: somente se o trâmite for cumprido pela chefia imediata
*       Outra possibilidade é se o usuário cumprir algum trâmite no serviço
*       Outra possibilidade é se o serviço for de interesse de toda a unidade e o usuário for lotado nela
*    2: Se o usuário é o solicitante da solicitacao ou se é um interessado na sua execução
*    1: Se o usuário é o cadastrador da solicitação
*    0: Se o usuário não tem acesso à solicitação
*    Se o usuário enquadrar-se em mais de uma das situações acima, o retorno será a
*    soma das situações. Assim,
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
*    16 a 31 - se o usuário deve cumprir o trâmite em que a solicitação está
***********************************************************************************/
  w_sq_servico             siw.siw_menu.sq_menu%type;
  w_acesso_geral           siw.siw_menu.acesso_geral%type;
  w_modulo                 siw.siw_menu.sq_modulo%type;
  w_sigla                  siw.siw_menu.sigla%type;
  w_username               siw.sg_autenticacao.sq_pessoa%type;
  w_sq_unidade_lotacao     siw.sg_autenticacao.sq_unidade%type;
  w_gestor_seguranca       siw.sg_autenticacao.gestor_seguranca%type;  
  w_gestor_sistema         siw.sg_autenticacao.gestor_sistema%type;
  w_sq_unidade_executora   siw.siw_menu.sq_unid_executora%type;        -- Unidade executora do serviço
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

 -- Recupera as informações da opção à qual a solicitação pertence
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
 
 -- Verifica se o usuário está ativo
 If w_usuario_ativo = 'N' Then
   -- Se não estiver, retorna 0
   Return(result);
 End If;
 -- Verifica se o usuário é o cadastrador
 If p_usuario = w_cadastrador Then Result := 1; End If;
 
 -- Verifica se o usuário é o executor
 If p_usuario = w_executor Then Result := 1; End If;

 -- Verifica se o usuário é o solicitante
 If w_solicitante = p_usuario Then 
    Result                   := Result + 2; 
    w_unidade_beneficiario   := w_sq_unidade_lotacao;
 Else 
    -- Verifica se o usuário é interessado na demanda ou se já participou em algum momento
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
          -- Verifica se o usuário é interessado no projeto ou se já participou em algum momento
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
                -- Verifica se é responsável por uma etapa de projeto
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
    
    -- recupera o código e a lotação do solicitante, para verificar, mais abaixo,
    -- se o usuário é chefe dele
    select a.solicitante, b.sq_unidade
      into w_solicitante, w_unidade_beneficiario
      from siw.siw_solicitacao a,
           siw.sg_autenticacao b 
     where (a.solicitante = b.sq_pessoa)
       and a.sq_siw_solicitacao = p_solicitacao;
 End If;
 
 -- Se o serviço for vinculado à unidade
 If w_vinculacao = 'U' Then
    -- Verifica se o usuário está lotado ou se é titular/substituto 
    -- da unidade de CADASTRAMENTO da solicitação
    If w_sq_unidade_lotacao   = w_unidade_solicitante or
       w_sq_pessoa_titular    = p_usuario             or
       w_sq_pessoa_substituto = p_usuario
    Then
       Result := Result + 4; 
    Else
       -- Verifica se participa em algum trâmite do serviço
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
 -- Caso contrário, se o serviço for vinculado à pessoa
 Elsif w_vinculacao = 'P' Then

    -- Verifica se o usuário é responsável pela unidade do solicitante
    select count(*) into w_chefe_beneficiario
      from siw.eo_unidade_resp a
     where a.sq_unidade = w_unidade_beneficiario 
       and a.sq_pessoa  = p_usuario
       and a.fim        is null;

    -- Verifica se o usuário é o titular ou o substituto da unidade
    -- de lotação do BENEFICIÁRIO da solicitação, ou se participa em algum trâmite
    -- do serviço
    If w_chefe_beneficiario > 0 Then 
       Result := Result + 4; 
    Else
       -- Verifica se participa em algum trâmite do serviço
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
 
 -- Verifica se o usuário é gestor do sistema ou do módulo à qual a solicitação pertence
 select count(*)
   into w_existe
   from siw.sg_pessoa_modulo a
  where a.sq_pessoa = p_usuario
    and a.sq_modulo = w_modulo;
 If w_existe > 0 or w_gestor_sistema = 'S' Then
    Result := Result + 8;
 End If;


 -- Verifica se o usuário tem permissão para cumprir o trâmite atual da solicitação
 -- Uma das possibilidades é o trâmite ser cumprido pelo titular/substituto
 -- da unidade do cadastrador ou da solicitação
 If w_chefia_imediata = 'S' Then
 
    -- Se o serviço for vinculado à unidade, testa a unidade que cadastrou a solicitação.
    -- Caso contrário, testa a unidade de lotação do solicitante.
    If w_vinculacao = 'U' Then
       w_unidade_atual := w_unidade_solicitante;
    Elsif w_vinculacao = 'P' Then
       w_unidade_atual := w_unidade_beneficiario;
    End If;

    loop
       w_existe := 1;
       for crec in c_Unidade (w_unidade_atual) loop
           -- Se o serviço for vinculado à pessoa:
           --   a) se o solicitante não for o titular nem o substituto, aparece apenas na mesa do titular e do substituto;
           --   a) se o solicitante for o substituto, aparece na mesa do titular;
           --   b) se o solicitante for o titular:
           --      b.1) se há uma unidade superior ela deve ser assinada por chefes superiores;
           --      b.2) se não há uma unidade superior ela deve ser assinada pelo substituto.
           -- Se o serviço for vinculado à unidade:
           --   a) A solicitação aparece na mesa do titular e do substituto da unidade
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
                 -- Entrar aqui significa que não foi encontrado nenhum responsável cadastrado no sistema,
                 -- o que é um erro. No módulo de estrutura organizacional, informar os responsáveis.
                 w_existe           := 1;
              End If;
           End If;
       end loop;
       
       If w_existe = 1 Then
          exit;
       End If;
    end loop;

 -- Outra possibilidade é o trâmite ser cumprido pelo titular/substituto
 -- da unidade de execução
 Elsif w_chefia_imediata = 'U' Then
    -- Verifica se o usuário é responsável pela unidade executora
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
 -- Outra possibilidade é a solicitação estar concluída e pendente de opinião pelo
 -- solicitante
 Elsif w_sigla_situacao = 'AT' and  w_solicitante = p_Usuario and w_consulta_opiniao = 'S' and w_opiniao_solicitante is null Then
    Result := Result + 16;
 Else
    -- Outra possibilidade é o trâmite ser cumprido por uma pessoa que tenha
    -- permissão para isso
    select count(*) into w_existe 
      from siw.sg_tramite_pessoa a 
     where a.sq_pessoa          = p_usuario
       and a.sq_pessoa_endereco = w_sq_endereco_unidade 
       and a.sq_siw_tramite     = w_sq_siw_tramite;
    If w_existe > 0 Then 
       Result := Result + 16; 
    Else
       -- Outra possibilidade é a solicitação estar sendo executada pelo usuário
       If w_executor = p_usuario Then 
          Result := Result + 16;
       Else
          -- Se for módulo de orçamento, outra possibilidade é a solicitação ter metas e o usuário ser:
          -- responsável pelo monitoramento, tit/subst do setor responsável pelo monitoramento ou
          -- tit/subst da unidade executora do serviço.
          If p_usuario = w_solicitante Then
             Result := Result + 16;
          Else
             -- Verifica se o usuário é responsável pela unidade executora
             select count(*) into w_existe
               from siw.eo_unidade_resp a
              where a.sq_unidade = w_sq_unidade_executora
                and a.sq_pessoa  = p_usuario
                and a.fim        is null;
             If w_existe > 0 Then 
                Result := Result + 16;
             Else
                -- Verifica, nas demandas, se o usuário é responsável pela unidade responsável pelo monitoramento
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
                   -- Verifica, nas demandas, se o usuário é responsável pela unidade responsável pelo monitoramento
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
