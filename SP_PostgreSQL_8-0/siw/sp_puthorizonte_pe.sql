create or replace FUNCTION sp_PutHorizonte_PE
   (p_operacao  varchar,
    p_chave     numeric,
    p_cliente   numeric,
    p_nome      varchar,
    p_ativo     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pe_horizonte (sq_pehorizonte, cliente, nome, ativo)
      (select sq_pehorizonte.nextval, p_cliente, p_nome,  p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pe_horizonte
         set 
             cliente      = p_cliente,
             nome         = p_nome,
             ativo        = p_ativo
       where sq_pehorizonte = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pe_horizonte
       where sq_pehorizonte = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;