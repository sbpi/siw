create or replace function SP_GetVincKindList
   (p_cliente     numeric,
    p_ativo       varchar,
    p_tipo_pessoa varchar,
    p_nome        varchar,
    p_interno     varchar,
    p_result      refcursor
   ) returns refcursor as $$
begin
   -- Recupera os tipos de vinculos existentes
   open p_result for 
      select a.sq_tipo_vinculo, a.nome, a.padrao,
             a.interno, a.contratado, 
             a.ativo, b.nome as sq_tipo_pessoa
        from co_tipo_vinculo a, 
             co_tipo_pessoa  b
       where a.sq_tipo_pessoa = b.sq_tipo_pessoa
         and a.cliente        = p_cliente
         and ((p_ativo       is null) or (p_ativo       is not null and a.ativo   = p_ativo))
         and ((p_tipo_pessoa is null) or (p_tipo_pessoa is not null and b.nome    = p_tipo_pessoa))
         and ((p_nome        is null) or (p_nome        is not null and upper(a.nome) like '%'||acentos(p_nome, null)||'%'))
         and ((p_interno     is null) or (p_interno     is not null and a.interno = p_interno))
     order by a.interno desc, b.nome, a.ordem;
   return p_result;
end; $$ language plpgsql volatile;