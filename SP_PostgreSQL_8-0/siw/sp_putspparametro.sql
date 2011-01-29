create or replace FUNCTION SP_PutSPParametro
   (p_operacao        varchar,
    p_chave           numeric,
    p_chave_aux       numeric,
    p_sq_dado_tipo    numeric,
    p_nome            varchar,
    p_descricao       varchar,
    p_tipo            varchar,
    p_ordem           numeric   
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_sp_param
        (sq_sp_param, sq_stored_proc, sq_dado_tipo, nome, descricao, tipo, ordem)
        (select nextVal('sq_sp_param'), p_chave, p_sq_dado_tipo, p_nome, p_descricao, p_tipo, p_ordem);
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_sp_param 
      where sq_sp_param = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;