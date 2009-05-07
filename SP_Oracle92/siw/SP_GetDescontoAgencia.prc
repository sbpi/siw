create or replace procedure SP_GetDescontoAgencia
   (p_cliente         in  number,
    p_chave           in  number   default null,
    p_fornecedor      in  number   default null, -- Agência
    --p_inicio          in  number   default null,    
    --p_fim             in  number   default null,
    p_ativo           in  varchar2 default null,
    --p_desconto        in  number   default null,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera as companhias de viagem
   open p_result for
      select a.sq_desconto_agencia as chave,
             a.cliente,
             b.nome,
             a.faixa_inicio,
             a.faixa_fim,
             a.desconto,
             a.ativo
        from pd_desconto_agencia a
       inner join co_pessoa b on (a.agencia_viagem = b.sq_pessoa)
       where a.cliente = p_cliente
         and (p_chave      is null or (p_chave      is not null and a.sq_desconto_agencia = p_chave))
         and (p_fornecedor is null or (p_fornecedor is not null and a.agencia_viagem      = p_fornecedor))
         and (p_ativo      is null or (p_ativo      is not null and a.ativo             = p_ativo));
end SP_GetDescontoAgencia;
/
