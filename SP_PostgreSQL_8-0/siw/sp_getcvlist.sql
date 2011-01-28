create or replace FUNCTION SP_GetCVList
   (p_cliente              numeric,
    p_sq_formacao          numeric,
    p_sq_idioma            numeric,
    p_sexo                 varchar,
    p_nome                 varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   if p_sq_idioma is null Then
      open p_result for 
         select b.nome_resumido, d.nome nm_formacao, a.sq_pessoa,
                case c.sexo when 'M' then 'Masculino' else 'Feminino' end nm_sexo
           from cv_pessoa a
                left outer join co_pessoa        b on a.sq_pessoa   = b.sq_pessoa
                left outer join co_pessoa_fisica c on a.sq_pessoa   = c.sq_pessoa
                left outer join co_formacao      d on c.sq_formacao = d.sq_formacao
          where b.sq_pessoa_pai = p_cliente
            and ((p_sq_formacao is null) or (p_sq_formacao is not null and c.sq_formacao = p_sq_formacao))
            and ((p_sexo        is null) or (p_sexo        is not null and c.sexo        = p_sexo))
            and ((p_nome        is null) or (p_nome        is not null and upper(b.nome) like '%'||upper(p_nome)||'%'));                        
   Else
      open p_result for 
         select b.nome_resumido, d.nome nm_formacao, a.sq_pessoa,
                case c.sexo when 'M' then 'Masculino' else 'Feminino' end nm_sexo
           from cv_pessoa a
                left outer join co_pessoa        b on a.sq_pessoa   = b.sq_pessoa
                left outer join co_pessoa_fisica c on a.sq_pessoa   = c.sq_pessoa
                left outer join co_formacao      d on c.sq_formacao = d.sq_formacao
                left outer join cv_pessoa_idioma e on a.sq_pessoa   = e.sq_pessoa
          where b.sq_pessoa_pai = p_cliente
            and ((p_sq_formacao is null) or (p_sq_formacao is not null and c.sq_formacao = p_sq_formacao))
            and ((p_sexo        is null) or (p_sexo        is not null and c.sexo        = p_sexo))
            and ((p_nome        is null) or (p_nome        is not null and upper(b.nome) like '%'||upper(p_nome)||'%'))
            and ((p_sq_idioma   is null) or (p_sq_idioma   is not null and e.sq_idioma = p_sq_idioma));                      
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;