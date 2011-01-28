create or replace FUNCTION SP_PutTTTronco
   (p_operacao           varchar,
    p_chave              numeric,
    p_cliente            numeric,
    p_sq_central_fone    numeric,
    p_sq_pessoa_telefone numeric,
    p_codigo             varchar,
    p_ativo              varchar 
    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
   
   insert into tt_tronco
     (sq_tronco, cliente, sq_central_fone, sq_pessoa_telefone, codigo, ativo)
     (select sq_tronco.nextVal, p_cliente, p_sq_central_fone, p_sq_pessoa_telefone, p_codigo, p_ativo from dual);
     
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update tt_tronco
      set sq_tronco          = p_chave,
          cliente            = p_cliente,
          sq_central_fone    = p_sq_central_fone,
          sq_pessoa_telefone = p_sq_pessoa_telefone,
          codigo             = p_codigo,
          ativo              = p_ativo
        where sq_tronco = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
       DELETE FROM tt_tronco
        where sq_tronco = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;