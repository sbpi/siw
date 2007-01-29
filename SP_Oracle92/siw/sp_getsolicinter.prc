create or replace procedure SP_GetSolicInter
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
   w_modulo siw_modulo.sigla%type;
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
            select a.*, b.nome, b.nome_resumido, b.nome_indice, b.nome_resumido_ind, d.sigla lotacao,
                   c.email
              from gd_demanda_interes  a,
                   co_pessoa           b,
                   sg_autenticacao     c,
                   eo_unidade          d
             where a.sq_pessoa          = b.sq_pessoa
               and a.sq_pessoa          = c.sq_pessoa           
               and c.sq_unidade         = d.sq_unidade
               and a.sq_siw_solicitacao = p_chave;
      Elsif p_restricao = 'REGISTRO' Then
         -- Recupera as demandas que o usuário pode ver
         open p_result for 
            select a.*, b.nome, b.nome_resumido, b.nome_indice, b.nome_resumido_ind
              from gd_demanda_interes a,
                   co_pessoa          b
             where a.sq_pessoa          = b.sq_pessoa
               and a.sq_siw_solicitacao = p_chave
               and a.sq_pessoa          = p_chave_aux;
      End If;
   Elsif w_modulo = 'PR' or w_modulo = 'OR' or w_modulo = 'IS' Then -- Se for o módulo de projetos
      If p_restricao = 'LISTA' Then
         -- Recupera as demandas que o usuário pode ver
         open p_result for 
            select a.*, b.nome, b.nome_resumido, b.nome_indice, b.nome_resumido_ind, d.sigla lotacao,
                   c.email
              from pj_projeto_interes  a,
                   co_pessoa           b,
                   sg_autenticacao     c,
                   eo_unidade          d
             where a.sq_pessoa          = b.sq_pessoa
               and a.sq_pessoa          = c.sq_pessoa           
               and c.sq_unidade         = d.sq_unidade
               and a.sq_siw_solicitacao = p_chave;
      Elsif p_restricao = 'REGISTRO' Then
         -- Recupera as demandas que o usuário pode ver
         open p_result for 
            select a.*, b.nome, b.nome_resumido, b.nome_indice, b.nome_resumido_ind
              from pj_projeto_interes a,
                   co_pessoa          b
             where a.sq_pessoa          = b.sq_pessoa
               and a.sq_siw_solicitacao = p_chave
               and a.sq_pessoa          = p_chave_aux;
      End If;
   Elsif w_modulo = 'PE' Then -- Se for o módulo de planejamento estratégico
      If p_restricao = 'LISTA' Then
         -- Recupera as demandas que o usuário pode ver
         open p_result for 
            select a.*, 
                   b.nome, b.nome_resumido, b.nome_indice, b.nome_resumido_ind,
                   c.email,
                   d.sigla lotacao,
                   e.nome nm_tipo_interessado
              from siw_solicitacao_interessado       a
                   inner   join co_pessoa            b on (a.sq_pessoa           = b.sq_pessoa)
                   inner   join sg_autenticacao      c on (a.sq_pessoa           = c.sq_pessoa)
                     inner join eo_unidade           d on (c.sq_unidade          = d.sq_unidade)
                   inner   join siw_tipo_interessado e on (a.sq_tipo_interessado = e.sq_tipo_interessado)
             where a.sq_siw_solicitacao = p_chave;
      Elsif p_restricao = 'REGISTRO' Then
         -- Recupera as demandas que o usuário pode ver
         open p_result for 
            select a.*, 
                   b.nome, b.nome_resumido, b.nome_indice, b.nome_resumido_ind
              from siw_solicitacao_interessado a
                   inner join co_pessoa        b on (a.sq_pessoa = b.sq_pessoa)
             where a.sq_siw_solicitacao = p_chave
               and a.sq_pessoa          = p_chave_aux;
      End If;   
   End If;
End SP_GetSolicInter;
/
