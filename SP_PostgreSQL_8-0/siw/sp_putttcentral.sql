create or replace FUNCTION SP_PutTTCentral
   (p_operacao           varchar,
    p_chave              numeric,
    p_cliente            numeric,
    p_sq_pessoa_endereco numeric,
    p_arquivo_bilhetes   varchar,
    p_recupera_bilhetes  varchar 
    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
   
   insert into tt_central
     (sq_central_fone, cliente, sq_pessoa_endereco, arquivo_bilhetes, recupera_bilhetes)
     (select sq_central_telefonica.nextVal, p_cliente, p_sq_pessoa_endereco, p_arquivo_bilhetes, p_recupera_bilhetes);
     
   Elsif p_operacao = 'A' Then
      -- Altera registro
     update tt_central
       set 
       sq_central_fone    = p_chave,
       cliente            = p_cliente,
       sq_pessoa_endereco = p_sq_pessoa_endereco,
       arquivo_bilhetes   = p_arquivo_bilhetes,
       recupera_bilhetes  = p_recupera_bilhetes
       where sq_central_fone = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
       DELETE FROM tt_central
        where sq_central_fone = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;