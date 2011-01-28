create or replace FUNCTION SP_GetTramiteSolic
   (p_chave      numeric,
    p_chave_aux  numeric,
    p_endereco   numeric,
    p_restricao  varchar,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
   
   w_tramite siw_tramite%rowtype;
   w_solic   siw_solicitacao%rowtype;
   w_menu    siw_menu%rowtype;
BEGIN
   If p_restricao is null Then
     open p_result for
     select a.sq_siw_tramite, a.sq_menu, a.nome as nm_tramite, a.ordem, a.chefia_imediata,
            c.nome as nm_pessoa, c.nome_resumido as nm_resumido, 
            d.sq_pessoa, d.username, d.email, 
            e.nome as nm_unidade, e.sigla as sg_unidade,
            acesso(p_chave, d.sq_pessoa, a.sq_siw_tramite) as acesso
       from siw_tramite                      a,
            siw_solicitacao                  a1
            inner       join siw_menu        b  on (a1.sq_menu        = b.sq_menu)
              inner     join co_pessoa       c  on (b.sq_pessoa       = c.sq_pessoa_pai)
                inner   join sg_autenticacao d  on (c.sq_pessoa       = d.sq_pessoa)
                  inner join eo_unidade      e  on (d.sq_unidade      = e.sq_unidade)
                inner   join co_tipo_vinculo f  on (c.sq_tipo_vinculo = f.sq_tipo_vinculo)
      where a1.sq_siw_solicitacao = p_chave
        and a.sq_siw_tramite      = coalesce(p_chave_aux,a1.sq_siw_tramite)
        and ((a.sigla             = 'CI'  and a1.cadastrador = c.sq_pessoa) or
             (a.sigla             <> 'CI' and ((a.destinatario = 'S' and a1.executor = c.sq_pessoa) or 
                                               (a.destinatario = 'N' and 15 < (select acesso(p_chave, d.sq_pessoa, a.sq_siw_tramite)))
                                              )
             )
            )
     order by a.nome, c.nome_resumido;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;