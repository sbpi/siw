create or replace FUNCTION SP_GetFoneData
   (p_chave        numeric,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados do endereco informado
   open p_result for 
      select b.*, c.sq_pais, c.co_uf 
      from co_pessoa_telefone b, co_cidade c  
      where b.sq_cidade          = c.sq_cidade 
        and b.sq_pessoa_telefone = p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;