create or replace procedure SP_GetSolicAreas
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out siw.sys_refcursor) is
   w_modulo  siw_modulo.sigla%type;
   w_modulo2 siw_menu.sigla%type;
begin
   -- Recupera o módulo da solicitacao para decidir onde buscará os interessados
   select c.sigla into w_modulo
     from siw_solicitacao a, siw_menu b, siw_modulo c
    where a.sq_menu            = b.sq_menu
      and b.sq_modulo          = c.sq_modulo
      and a.sq_siw_solicitacao = p_chave;

   If w_modulo = 'DM' Then -- Se for o módulo de demandas
      If p_restricao = 'LISTA' Then
         -- Recupera as demandas que o usuário pode ver
         open p_result for
            select a.*, b.nome, b.informal, b.vinculada, b.adm_central
              from gd_demanda_envolv   a,
                   eo_unidade          b
             where a.sq_unidade         = b.sq_unidade
                and a.sq_siw_solicitacao = p_chave;
      Elsif p_restricao = 'REGISTRO' Then
         -- Recupera as demandas que o usuário pode ver
         open p_result for
             select a.*, b.nome, b.informal, b.vinculada, b.adm_central
               from gd_demanda_envolv  a,
                    eo_unidade         b
              where a.sq_unidade         = b.sq_unidade
               and a.sq_siw_solicitacao = p_chave
               and a.sq_unidade         = p_chave_aux;
      End If;
   Elsif w_modulo = 'PR' or w_modulo = 'OR' Then -- Se for o módulo de projetos
      -- Recupera as demandas que o usuário pode ver
      If w_modulo = 'OR' Then
         select b.sigla into w_modulo2
           from siw_solicitacao a, siw_menu b, siw_modulo c
          where a.sq_menu            = b.sq_menu
            and b.sq_modulo          = c.sq_modulo
            and a.sq_siw_solicitacao = p_chave;
         If w_modulo2 = 'ORPCAD' Then
            open p_result for 
               select a.*, b.nome, b.informal, b.vinculada, b.adm_central
                 from gd_demanda_envolv   a,
                      eo_unidade          b
                where a.sq_unidade         = b.sq_unidade
                   and a.sq_siw_solicitacao = p_chave
                   and (p_chave_aux is null or (p_chave_aux is not null and a.sq_unidade = p_chave_aux));
         Else
            open p_result for 
               select a.*, b.nome, b.informal, b.vinculada, b.adm_central
                 from gd_demanda_envolv   a,
                      eo_unidade          b
                where a.sq_unidade         = b.sq_unidade
                   and a.sq_siw_solicitacao = p_chave
                   and (p_chave_aux is null or (p_chave_aux is not null and a.sq_unidade = p_chave_aux));
         End If;
      Else
         open p_result for 
            select a.*, b.nome, b.informal, b.vinculada, b.adm_central
              from pj_projeto_envolv   a,
                   eo_unidade          b
             where a.sq_unidade         = b.sq_unidade
                and a.sq_siw_solicitacao = p_chave
                and (p_chave_aux is null or (p_chave_aux is not null and a.sq_unidade = p_chave_aux));
      End If;
   End If;
end SP_GetSolicAreas;
/
