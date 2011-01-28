create or replace FUNCTION SP_GetPessoaTel
   (p_chave           numeric,
    p_result             REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de Tabela
   
     open p_result for 
     select *
     from co_pessoa_telefone      a
     where ((p_chave           is null) or (p_chave           is not null and a.sq_pessoa_telefone = p_chave));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;