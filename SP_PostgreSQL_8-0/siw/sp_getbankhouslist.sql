create or replace FUNCTION SP_GetBankHousList
   (p_sq_banco    numeric,
    p_nome        varchar,
    p_codigo      varchar,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados da agência bancária
   open p_result for 
      select a.sq_agencia, b.codigo sq_banco, a.nome, a.codigo,
             case a.padrao when 'S' then 'Sim' else 'Não' end padrao,
             case a.ativo  when 'S' then 'Sim' else 'Não' end ativo
        from co_agencia a, co_banco b
       where a.sq_banco   = b.sq_banco
         and b.sq_banco   = p_sq_banco
         and (p_nome   is null or (p_nome   is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
         and (p_codigo is null or (p_codigo is not null and a.codigo = p_codigo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;