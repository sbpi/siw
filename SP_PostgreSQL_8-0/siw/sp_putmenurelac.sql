create or replace FUNCTION SP_PutMenuRelac
   (p_operacao            varchar,
    p_servico_cliente     numeric,
    p_servico_fornecedor  numeric,
    p_sq_siw_tramite      varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_menu_relac (servico_cliente,   servico_fornecedor,  sq_siw_tramite ) 
      values                     (p_servico_cliente, p_servico_fornecedor, p_sq_siw_tramite);
   Elsif p_operacao = 'E' Then
      -- Exclui todos os registros do cliente desejado
      DELETE FROM siw_menu_relac where servico_cliente = p_servico_cliente and servico_fornecedor = p_servico_fornecedor;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;