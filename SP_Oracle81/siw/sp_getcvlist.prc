create or replace procedure SP_GetCVList
   (p_cliente              in number,
    p_sq_formacao          in number   default null,
    p_sq_idioma            in number   default null,
    p_sexo                 in varchar2 default null,
    p_nome                 in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   if p_sq_idioma is null Then
      open p_result for
         select b.nome_resumido, d.nome nm_formacao, a.sq_pessoa,
                decode(c.sexo,'M','Masculino','Feminino') nm_sexo
           from cv_pessoa a,
                co_pessoa        b,
                co_pessoa_fisica c,
                co_formacao      d
          where a.sq_pessoa   = b.sq_pessoa (+)
            and a.sq_pessoa   = c.sq_pessoa (+)
            and c.sq_formacao = d.sq_formacao (+)
            and b.sq_pessoa_pai = p_cliente
            and ((p_sq_formacao is null) or (p_sq_formacao is not null and c.sq_formacao = p_sq_formacao))
            and ((p_sexo        is null) or (p_sexo        is not null and c.sexo        = p_sexo))
            and ((p_nome        is null) or (p_nome        is not null and upper(b.nome) like '%'||upper(p_nome)||'%'));
   Else
      open p_result for
         select b.nome_resumido, d.nome nm_formacao, a.sq_pessoa,
                decode(c.sexo,'M','Masculino','Feminino') nm_sexo
           from cv_pessoa a,
                co_pessoa        b,
                co_pessoa_fisica c,
                co_formacao      d,
                cv_pessoa_idioma e
          where a.sq_pessoa   = b.sq_pessoa (+)
            and a.sq_pessoa   = c.sq_pessoa (+)
            and c.sq_formacao = d.sq_formacao (+)
            and a.sq_pessoa   = e.sq_pessoa (+)
            and b.sq_pessoa_pai = p_cliente
            and ((p_sq_formacao is null) or (p_sq_formacao is not null and c.sq_formacao = p_sq_formacao))
            and ((p_sexo        is null) or (p_sexo        is not null and c.sexo        = p_sexo))
            and ((p_nome        is null) or (p_nome        is not null and upper(b.nome) like '%'||upper(p_nome)||'%'))
            and ((p_sq_idioma   is null) or (p_sq_idioma   is not null and e.sq_idioma = p_sq_idioma));
   End If;
end SP_GetCVList;
/

