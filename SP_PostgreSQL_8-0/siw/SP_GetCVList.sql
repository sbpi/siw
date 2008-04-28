CREATE OR REPLACE FUNCTION siw.SP_GetCVList
   (p_cliente              numeric,
    p_sq_formacao          numeric,
    p_sq_idioma            numeric,
    p_sexo                 varchar,
    p_nome                 varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   if p_sq_idioma is null Then
      open p_result for
         select b.nome_resumido, d.nome as nm_formacao, a.sq_pessoa,
                case c.sexo when 'M' then 'Masculino' else 'Feminino' end as nm_sexo
           from siw.cv_pessoa a
                left outer join siw.co_pessoa        b on a.sq_pessoa   = b.sq_pessoa
                left outer join siw.co_pessoa_fisica c on a.sq_pessoa   = c.sq_pessoa
                left outer join siw.co_formacao      d on c.sq_formacao = d.sq_formacao
          where b.sq_pessoa_pai = p_cliente
            and ((p_sq_formacao is null) or (p_sq_formacao is not null and c.sq_formacao = p_sq_formacao))
            and ((p_sexo        is null) or (p_sexo        is not null and c.sexo        = p_sexo))
            and ((p_nome        is null) or (p_nome        is not null and upper(b.nome) like '%'||upper(p_nome)||'%'));
   Else
      open p_result for
         select b.nome_resumido, d.nome as nm_formacao, a.sq_pessoa,
                case c.sexo when 'M' then 'Masculino' else 'Feminino' end as nm_sexo
           from siw.cv_pessoa a
                left outer join siw.co_pessoa        b on a.sq_pessoa   = b.sq_pessoa
                left outer join siw.co_pessoa_fisica c on a.sq_pessoa   = c.sq_pessoa
                left outer join siw.co_formacao      d on c.sq_formacao = d.sq_formacao
                left outer join siw.cv_pessoa_idioma e on a.sq_pessoa   = e.sq_pessoa
          where b.sq_pessoa_pai = p_cliente
            and ((p_sq_formacao is null) or (p_sq_formacao is not null and c.sq_formacao = p_sq_formacao))
            and ((p_sexo        is null) or (p_sexo        is not null and c.sexo        = p_sexo))
            and ((p_nome        is null) or (p_nome        is not null and upper(b.nome) like '%'||upper(p_nome)||'%'))
            and ((p_sq_idioma   is null) or (p_sq_idioma   is not null and e.sq_idioma = p_sq_idioma));
   End If;
   return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.Sp_GetColuna
   (p_cliente      numeric,
    p_chave        numeric, 
    p_sq_tabela    numeric,
    p_sq_dado_tipo varchar,
    p_sq_sistema   numeric, 
    p_sq_usuario   numeric, 
    p_nome         varchar,
    p_esq_tab      numeric) OWNER TO siw;
