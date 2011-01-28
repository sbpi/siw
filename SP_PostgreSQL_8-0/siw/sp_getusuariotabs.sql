create or replace FUNCTION SP_GetUsuarioTabs
   (p_chave     numeric,
    p_chave_aux numeric,
    p_sq_tabela numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de índic
   open p_result for 
      select a.sq_usuario chave, a.nome, a.descricao, a.sq_sistema
        from dc_usuario        a
          inner join dc_tabela b on (b.sq_usuario = a.sq_usuario)
       where ((p_chave     is null) or (p_chave     is not null and b.sq_usuario = p_chave))
         and ((p_chave_aux is null) or (p_chave_aux is not null and b.sq_sistema = p_chave_aux))
         and ((p_sq_tabela is null) or (p_sq_tabela is not null and b.sq_tabela  = p_sq_tabela));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;