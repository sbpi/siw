create or replace procedure sp_getDocumentoAssunto
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_principal in varchar2 default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for
         select a.sq_siw_solicitacao as chave, a.sq_assunto as chave_aux, a.principal,
                case a.principal when 'S' then 'Sim' else 'Não' end as nm_principal,
                b.codigo, b.descricao, b.detalhamento, b.observacao,
                case c.sigla when 'ANOS' then b.fase_corrente_anos||' '||c.descricao when 'NAPL' then '---' else c.descricao end as guarda_corrente,
                case d.sigla when 'ANOS' then b.fase_intermed_anos||' '||d.descricao when 'NAPL' then '---' else d.descricao end as guarda_intermed,
                case e.sigla when 'ANOS' then b.fase_final_anos   ||' '||e.descricao when 'NAPL' then '---' else e.descricao end as guarda_final,
                f.descricao as destinacao_final
           from pa_documento_assunto        a
                inner   join pa_assunto     b on (a.sq_assunto           = b.sq_assunto)
                  inner join pa_tipo_guarda c on (b.fase_corrente_guarda = c.sq_tipo_guarda)
                  inner join pa_tipo_guarda d on (b.fase_intermed_guarda = d.sq_tipo_guarda)
                  inner join pa_tipo_guarda e on (b.fase_final_guarda    = e.sq_tipo_guarda)
                  inner join pa_tipo_guarda f on (b.destinacao_final     = f.sq_tipo_guarda)
          where (p_chave is null or (p_chave is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux is null or (p_chave_aux is not null and b.sq_assunto = p_chave_aux))
            and (p_principal is null or (p_principal is not null and a.principal  = p_principal));
   End If;
end sp_getDocumentoAssunto;
/
