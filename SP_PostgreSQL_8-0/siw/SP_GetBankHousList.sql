create or replace function SP_GetBankHousList
   (p_sq_banco   numeric,
    p_nome       varchar,
    p_codigo     varchar,
    p_result     refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados da agência bancária
   open p_result for 
      select a.sq_agencia, b.codigo as sq_banco, a.nome, a.codigo,
             case a.padrao when 'S' then 'Sim' else 'Não' end as padrao,
             case a.ativo  when 'S' then 'Sim' else 'Não' end as ativo
        from co_agencia a, co_banco b
       where a.sq_banco   = b.sq_banco
         and b.sq_banco   = p_sq_banco
         and (p_nome   is null or (p_nome   is not null and acentos(a.nome, null) like '%'||acentos(p_nome, null)||'%'))
         and (p_codigo is null or (p_codigo is not null and a.codigo = p_codigo));
  return p_result;
end; $$ language 'plpgsql' volatile;
