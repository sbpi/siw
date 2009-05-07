create or replace procedure SP_PutDescontoAgencia
   (p_operacao        in  varchar2,
    p_cliente         in  number   default null,
    p_chave           in  number   default null,
    p_agencia         in  number   default null, -- Agência
    p_inicio          in  number   default null,    
    p_fim             in  number   default null,
    p_desconto        in  number   default null,   
    p_ativo           in  varchar2 default null
    ) is 
begin

   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_desconto_agencia (
              sq_desconto_agencia,        cliente,   agencia_viagem,
              faixa_inicio, faixa_fim, desconto,   ativo)
      (select sq_desconto_agencia.nextval,p_cliente, p_agencia, 
              p_inicio,     p_fim,     p_desconto, p_ativo from dual);
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
      delete pd_desconto_agencia where sq_desconto_agencia = p_chave;
   End If;
end SP_PutDescontoAgencia;
/
