create or replace procedure SP_GetTramiteSolic
   (p_chave     in  number,
    p_chave_aux in  number,
    p_endereco  in  number default null,
    p_restricao in  varchar2 default null,
    p_result    out siw.sys_refcursor
   ) is
   
   w_tramite siw_tramite%rowtype;
   w_solic   siw_solicitacao%rowtype;
   w_menu    siw_menu%rowtype;
begin
   If p_restricao is null Then
      -- Recupera os dados do menu
      select * into w_menu from siw_menu where sq_menu = (select sq_menu from siw_solicitacao where sq_siw_solicitacao = p_chave);
      
      -- Recupera os dados da solicitacao informada
      select * into w_solic from siw_solicitacao a where a.sq_siw_solicitacao = p_chave;
      
      -- Recupera os dados do trâmite informado
      select * into w_tramite from siw_tramite a where a.sq_siw_tramite = p_chave_aux;
      
      If w_tramite.chefia_imediata = 'N' Then -- Se apenas usuários com permissão
         open p_result for
            select b.nome nm_tramite,
                   c.sq_pessoa, c.nome nm_pessoa, c.nome_resumido nm_resumido, 
                   d.email
              from sg_tramite_pessoa   a,
                   siw_tramite         b,
                   co_pessoa           c,
                   sg_autenticacao     d
             where a.sq_siw_tramite   = b.sq_siw_tramite
               and a.sq_pessoa        = c.sq_pessoa
               and a.sq_pessoa        = d.sq_pessoa
               and d.ativo            = 'S'
               and (p_endereco       is null or (p_endereco is not null and a.sq_pessoa_endereco = p_endereco))
               and a.sq_siw_tramite   = p_chave_aux
            UNION
            select b.nome nm_tramite,
                   c.sq_pessoa, c.nome nm_pessoa, c.nome_resumido nm_resumido, 
                   d.email
              from siw_solicitacao     a,
                   siw_tramite         b,
                   co_pessoa           c,
                   sg_autenticacao     d
             where a.cadastrador      = c.sq_pessoa
               and a.cadastrador      = d.sq_pessoa
               and d.ativo            = 'S'
               and b.ordem            = 1
               and a.sq_siw_solicitacao = p_chave
               and b.sq_siw_tramite     = p_chave_aux
            UNION
            select b.nome nm_tramite,
                   c.sq_pessoa, c.nome nm_pessoa, c.nome_resumido nm_resumido, 
                   d.email
              from siw_solicitacao     a,
                   siw_tramite         b,
                   co_pessoa           c,
                   sg_autenticacao     d
             where a.solicitante      = c.sq_pessoa
               and a.solicitante      = d.sq_pessoa
               and d.ativo            = 'S'
               and b.ordem            = 1
               and a.sq_siw_solicitacao = p_chave
               and b.sq_siw_tramite     = p_chave_aux;
      Elsif w_tramite.chefia_imediata = 'S' and w_menu.vinculacao = 'U' Then -- Se chefia da unidade solicitante e vinculado à unidade
         open p_result for
            select b.nome nm_tramite,
                   c.sq_pessoa, c.nome nm_pessoa, c.nome_resumido nm_resumido, 
                   d.email
              from sg_tramite_pessoa   a,
                   siw_tramite         b,
                   co_pessoa           c,
                   sg_autenticacao     d
             where a.sq_siw_tramite   = b.sq_siw_tramite
               and a.sq_pessoa        = c.sq_pessoa
               and a.sq_pessoa        = d.sq_pessoa
               and d.ativo            = 'S'
               and (p_endereco       is null or (p_endereco is not null and a.sq_pessoa_endereco = p_endereco))
               and a.sq_siw_tramite   = p_chave_aux
            UNION
            select b.nome nm_tramite,
                   d.sq_pessoa, d.nome nm_pessoa, d.nome_resumido nm_resumido, d.email
              from siw_solicitacao     a,
                   siw_tramite         b,
                   eo_unidade          c,
                   (select w.sq_unidade, x.sq_pessoa, x.nome, x.nome_resumido, y.email
                      from eo_unidade_resp     w,
                           co_pessoa           x,
                           sg_autenticacao     y
                     where w.sq_pessoa        = x.sq_pessoa
                       and x.sq_pessoa        = y.sq_pessoa
                       and y.ativo            = 'S'
                       and w.tipo_respons     = 'T'
                       and w.fim              is null
                   )                   d
             where a.sq_unidade         = c.sq_unidade
               and c.sq_unidade         = d.sq_unidade
               and a.sq_siw_solicitacao = p_chave
               and b.sq_siw_tramite     = p_chave_aux
            UNION
            select b.nome nm_tramite,
                   d.sq_pessoa, d.nome nm_pessoa, d.nome_resumido nm_resumido, d.email
              from siw_solicitacao     a,
                   siw_tramite         b,
                   eo_unidade          c,
                   (select w.sq_unidade, x.sq_pessoa, x.nome, x.nome_resumido, y.email
                      from eo_unidade_resp     w,
                           co_pessoa           x,
                           sg_autenticacao     y
                     where w.sq_pessoa        = x.sq_pessoa
                       and x.sq_pessoa        = y.sq_pessoa
                       and y.ativo            = 'S'
                       and w.tipo_respons     = 'S'
                       and w.fim              is null
                   )                   d
             where a.sq_unidade         = c.sq_unidade
               and c.sq_unidade         = d.sq_unidade
               and a.sq_siw_solicitacao = p_chave
               and b.sq_siw_tramite     = p_chave_aux;
      Elsif w_tramite.chefia_imediata = 'S' and w_menu.vinculacao = 'P' Then -- Se chefia da unidade solicitante e vinculado à pessoa
         open p_result for
            select b.nome nm_tramite,
                   c.sq_pessoa, c.nome nm_pessoa, c.nome_resumido nm_resumido, 
                   d.email
              from sg_tramite_pessoa   a,
                   siw_tramite         b,
                   co_pessoa           c,
                   sg_autenticacao     d
             where a.sq_siw_tramite   = b.sq_siw_tramite
               and a.sq_pessoa        = c.sq_pessoa
               and a.sq_pessoa        = d.sq_pessoa
               and d.ativo            = 'S'
               and (p_endereco       is null or (p_endereco is not null and a.sq_pessoa_endereco = p_endereco))
               and a.sq_siw_tramite   = p_chave_aux
            UNION
            select b.nome nm_tramite,
                   d.sq_pessoa, d.nome nm_pessoa, d.nome_resumido nm_resumido, d.email
              from siw_solicitacao     a,
                   sg_autenticacao     a1,
                   siw_tramite         b,
                   eo_unidade          c,
                   (select w.sq_unidade, x.sq_pessoa, x.nome, x.nome_resumido, y.email
                      from eo_unidade_resp     w,
                           co_pessoa           x,
                           sg_autenticacao     y
                     where w.sq_pessoa        = x.sq_pessoa
                       and x.sq_pessoa        = y.sq_pessoa
                       and y.ativo            = 'S'
                       and w.tipo_respons     = 'T'
                       and w.fim              is null
                   )                   d
             where a.solicitante        = a1.sq_pessoa
               and a1.sq_unidade        = c.sq_unidade
               and c.sq_unidade         = d.sq_unidade
               and a.sq_siw_solicitacao = p_chave
               and b.sq_siw_tramite     = p_chave_aux
            UNION
            select b.nome nm_tramite,
                   d.sq_pessoa, d.nome nm_pessoa, d.nome_resumido nm_resumido, d.email
              from siw_solicitacao     a,
                   sg_autenticacao     a1,
                   siw_tramite         b,
                   eo_unidade          c,
                   (select w.sq_unidade, x.sq_pessoa, x.nome, x.nome_resumido, y.email
                      from eo_unidade_resp     w,
                           co_pessoa           x,
                           sg_autenticacao     y
                     where w.sq_pessoa        = x.sq_pessoa
                       and x.sq_pessoa        = y.sq_pessoa
                       and y.ativo            = 'S'
                       and w.tipo_respons     = 'S'
                       and w.fim              is null
                   )                   d
             where a.solicitante        = a1.sq_pessoa
               and a1.sq_unidade        = c.sq_unidade
               and c.sq_unidade         = d.sq_unidade
               and a.sq_siw_solicitacao = p_chave
               and b.sq_siw_tramite     = p_chave_aux;
      Elsif w_tramite.chefia_imediata   = 'U' Then -- Se chefia da unidade executora e usuários com permissão
         open p_result for
            select b.nome nm_tramite,
                   c.sq_pessoa, c.nome nm_pessoa, c.nome_resumido nm_resumido, 
                   d.email
              from sg_tramite_pessoa   a,
                   siw_tramite         b,
                   co_pessoa           c,
                   sg_autenticacao     d
             where a.sq_siw_tramite   = b.sq_siw_tramite
               and a.sq_pessoa        = c.sq_pessoa
               and a.sq_pessoa        = d.sq_pessoa
               and d.ativo            = 'S'
               and (p_endereco       is null or (p_endereco is not null and a.sq_pessoa_endereco = p_endereco))
               and a.sq_siw_tramite   = p_chave_aux
            UNION
            select b.nome nm_tramite,
                   d.sq_pessoa, d.nome nm_pessoa, d.nome_resumido nm_resumido, d.email
              from siw_solicitacao     a,
                   siw_tramite         b,
                   siw_menu            c,
                   (select w.sq_unidade, x.sq_pessoa, x.nome, x.nome_resumido, y.email
                      from eo_unidade_resp     w,
                           co_pessoa           x,
                           sg_autenticacao     y
                     where w.sq_pessoa        = x.sq_pessoa
                       and x.sq_pessoa        = y.sq_pessoa
                       and y.ativo            = 'S'
                       and w.tipo_respons     = 'T'
                       and w.fim              is null
                   )                   d
             where a.sq_menu            = c.sq_menu
               and c.sq_unid_executora  = d.sq_unidade
               and a.sq_siw_solicitacao = p_chave
               and b.sq_siw_tramite     = p_chave_aux
            UNION
            select b.nome nm_tramite,
                   d.sq_pessoa, d.nome nm_pessoa, d.nome_resumido nm_resumido, d.email
              from siw_solicitacao     a,
                   siw_tramite         b,
                   siw_menu            c,
                   (select w.sq_unidade, x.sq_pessoa, x.nome, x.nome_resumido, y.email
                      from eo_unidade_resp     w,
                           co_pessoa           x,
                           sg_autenticacao     y
                     where w.sq_pessoa        = x.sq_pessoa
                       and x.sq_pessoa        = y.sq_pessoa
                       and y.ativo            = 'S'
                       and w.tipo_respons     = 'S'
                       and w.fim              is null
                   )                   d
             where a.sq_menu            = c.sq_menu
               and c.sq_unid_executora  = d.sq_unidade
               and a.sq_siw_solicitacao = p_chave
               and b.sq_siw_tramite     = p_chave_aux;
      End If;
   End If;
end SP_GetTramiteSolic;
/
