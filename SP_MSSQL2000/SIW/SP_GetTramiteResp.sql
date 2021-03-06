alter procedure dbo.SP_GetTramiteResp
   (@p_solic     int,
    @p_tramite   int = null,
    @p_restricao varchar(30) = null
   ) as
begin
   -- Recupera os usu�rios que podem cumprir o tr�mite informado da solicita��o
     select a.sq_siw_tramite, a.sq_menu, a.nome as nm_tramite, a.ordem, a.chefia_imediata,
            c.nome, c.nome_resumido, 
            d.sq_pessoa, d.username, d.email, 
            e.nome as nm_unidade, e.sigla as sg_unidade,
            dbo.acesso(@p_solic, d.sq_pessoa, a.sq_siw_tramite) as acesso
       from siw_tramite                      a,
            siw_solicitacao                  a1
            inner       join siw_menu        b  on (a1.sq_menu        = b.sq_menu)
              inner     join co_pessoa       c  on (b.sq_pessoa       = c.sq_pessoa_pai)
                inner   join sg_autenticacao d  on (c.sq_pessoa       = d.sq_pessoa)
                  inner join eo_unidade      e  on (d.sq_unidade      = e.sq_unidade)
                inner   join co_tipo_vinculo f  on (c.sq_tipo_vinculo = f.sq_tipo_vinculo)
      where a1.sq_siw_solicitacao = @p_solic
        and a.sq_siw_tramite      = coalesce(@p_tramite,a1.sq_siw_tramite)
        and ((a.sigla             = 'CI'  and a1.cadastrador = c.sq_pessoa) or
             (a.sigla             <> 'CI' and ((a.destinatario = 'S' and a1.executor = c.sq_pessoa) or 
                                               (a.destinatario = 'N' and 15 < (select dbo.acesso(@p_solic, d.sq_pessoa, a.sq_siw_tramite)))
                                              )
             )
            )
     order by a.nome, c.nome_resumido;
end
