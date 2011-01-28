create or replace FUNCTION SP_GetBankList
   (p_codigo     varchar,
    p_nome       varchar,
    p_ativo      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os bancos existentes
   open p_result for 
      select sq_banco, codigo, nome, ativo, codigo||' - '||nome descricao, padrao, exige_operacao
        from co_banco a 
       where (p_nome   is null or (p_nome   is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_codigo is null or (p_codigo is not null and codigo = p_codigo))
         and (p_ativo  is null or (p_ativo  is not null and ativo  = p_ativo))
      order by padrao desc, codigo;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;