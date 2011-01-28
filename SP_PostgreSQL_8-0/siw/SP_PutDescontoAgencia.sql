create or replace FUNCTION SP_PutDescontoAgencia
   (p_operacao         varchar,
    p_cliente          numeric,
    p_chave            numeric,
    p_agencia          numeric, 
    p_inicio           numeric,    
    p_fim              numeric,
    p_desconto         numeric,   
    p_ativo            varchar 
    ) RETURNS VOID AS $$ 
DECLARE
BEGIN

   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_desconto_agencia (
              sq_desconto_agencia,        cliente,   agencia_viagem,
              faixa_inicio, faixa_fim, desconto,   ativo)
      (select sq_desconto_agencia.nextval,p_cliente, p_agencia, 
              p_inicio,     p_fim,     p_desconto, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pd_desconto_agencia set
         agencia_viagem         = p_agencia,
         faixa_inicio           = p_inicio,
         faixa_fim              = p_fim,
         desconto               = p_desconto,
         ativo                  = p_ativo
      where sq_desconto_agencia = p_chave;
   Elsif p_operacao = 'E' Then 
      -- Exclui registro
      DELETE FROM pd_desconto_agencia where sq_desconto_agencia = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;