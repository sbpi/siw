create or replace FUNCTION SP_PutTTUsuarioCentral
   (p_operacao        varchar,
    p_chave           numeric,
    p_cliente         numeric,
    p_usuario         numeric,
    p_sq_central_fone numeric,
    p_codigo          varchar 
    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
   
   insert into tt_usuario (sq_usuario_central, cliente, usuario, sq_central_fone, codigo)
   values (sq_usuario_centralnextVal(''), p_cliente, p_usuario, p_sq_central_fone, p_codigo);
     
   Elsif p_operacao = 'A' Then
      -- Altera registro
     update tt_usuario set codigo = p_codigo where sq_usuario_central = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
       DELETE FROM tt_usuario where sq_usuario_central = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;