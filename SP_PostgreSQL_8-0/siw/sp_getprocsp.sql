create or replace FUNCTION SP_GetProcSP
   (p_chave     numeric,
    p_chave_aux numeric,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
  -- Recupera os recursos alocados a uma etapa do projeto
  open p_result for 
     select a.sq_procedure chave, a.sq_stored_proc chaveAux,
            b.nome nm_procedure, b.descricao ds_procedure,
            c.nome nm_sp, c.descricao ds_sp
     from         dc_proc_sp     a
       inner join dc_procedure   b on (a.sq_procedure   = b.sq_procedure)
       inner join dc_stored_proc c on (a.sq_stored_proc = c.sq_stored_proc)
      where ((p_chave     is null) or (p_chave     is not null and a.sq_procedure   = p_chave))
        and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_stored_proc = p_chave_aux));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;