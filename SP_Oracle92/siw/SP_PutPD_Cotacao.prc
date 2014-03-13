create or replace procedure SP_PutPD_Cotacao
   (p_chave               in number,
    p_moeda               in number    default null,
    p_valor               in number    default null,
    p_observacao          in varchar2  default null
   ) is
begin
  -- Atualiza o valor estimado para os bilhetes da viagem
  update pd_missao
     set sq_moeda_cotacao   = p_moeda,
         cotacao_valor      = p_valor,
         cotacao_observacao = p_observacao
   where sq_siw_solicitacao = p_chave;
end SP_PutPD_Cotacao;
/
