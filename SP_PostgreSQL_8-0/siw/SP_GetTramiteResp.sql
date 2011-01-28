create or replace FUNCTION SP_GetTramiteResp
   (p_solic      numeric,
    p_tramite    numeric,
    p_restricao  varchar,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE

BEGIN
   -- Recupera os usuários que podem cumprir o trâmite informado da solicitação
   open p_result for
     select a.sq_siw_tramite, a.sq_menu, a.nome as nm_tramite, a.ordem, a.chefia_imediata,
            c.nome, c.nome_resumido, 
            d.sq_pessoa, d.username, d.email, 
            e.nome as nm_unidade, e.sigla as sg_unidade,
            acesso(p_solic, d.sq_pessoa, a.sq_siw_tramite) as acesso,
            case when h.sq_pessoa is not null 
                 then 'N' 
                 else case when g.sq_pessoa is not null 
                           then 'S' 
                           else 'N' 
                      end 
            end as gestor_modulo
       from siw_tramite                        a,
            siw_solicitacao                    a1
            inner       join siw_menu          b  on (a1.sq_menu        = b.sq_menu)
              inner     join siw_modulo        b1 on (b.sq_modulo       = b1.sq_modulo)
              inner     join co_pessoa         c  on (b.sq_pessoa       = c.sq_pessoa_pai)
                inner   join sg_autenticacao   d  on (c.sq_pessoa       = d.sq_pessoa)
                  inner join eo_unidade        e  on (d.sq_unidade      = e.sq_unidade)
                inner   join co_tipo_vinculo   f  on (c.sq_tipo_vinculo = f.sq_tipo_vinculo)
                left    join sg_pessoa_modulo  g  on (b1.sq_modulo      = g.sq_modulo and
                                                      c.sq_pessoa       = g.sq_pessoa
                                                     )
                left    join sg_tramite_pessoa h  on (c.sq_pessoa       = h.sq_pessoa and
                                                      a1.sq_siw_tramite = h.sq_siw_tramite
                                                     )
      where a1.sq_siw_solicitacao = p_solic
        and a.sq_siw_tramite      = coalesce(p_tramite,a1.sq_siw_tramite)
        and ((a.sigla             = 'CI'  and a1.cadastrador = c.sq_pessoa) or
             (a.sigla             <> 'CI' and ((a.destinatario = 'S' and a1.executor = c.sq_pessoa) or 
                                               (a.destinatario = 'N' and 15 < (select acesso(p_solic, d.sq_pessoa, a.sq_siw_tramite) from dual))
                                              )
             )
            )
     order by a.nome, c.nome_resumido;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;