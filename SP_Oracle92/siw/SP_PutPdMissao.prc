create or replace procedure SP_PutPdMissao
   (p_operacao             in  varchar2,
    p_chave                in  number,
    p_valor_alimentacao    in  number,
    p_valor_transporte     in  number,
    p_valor_adcional       in  number,
    p_desconto_alimentacao in  number,
    p_desconto_transporte  in  number,
    p_restricao            in  varchar2
   ) is
begin
   If p_restricao is null Then
      -- Atualiza os valores financeiros em PD_MISSAO
      Update pd_missao
         set valor_alimentacao    = p_valor_alimentacao,
             valor_transporte     = p_valor_transporte,
             valor_adicional      = p_valor_adcional,
             desconto_alimentacao = p_desconto_alimentacao,
             desconto_transporte  = p_desconto_transporte
       where sq_siw_solicitacao = p_chave;
   End If;
end SP_PutPdMissao;
/
