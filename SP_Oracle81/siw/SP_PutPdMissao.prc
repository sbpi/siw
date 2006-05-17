create or replace procedure SP_PutPdMissao
   (p_operacao             in  varchar2 default null,
    p_chave                in  number,
    p_valor_alimentacao    in  number   default null,
    p_valor_transporte     in  number   default null,
    p_valor_adcional       in  number   default null,
    p_desconto_alimentacao in  number   default null,
    p_desconto_transporte  in  number   default null,
    p_pta                  in  varchar2 default null,
    p_emissao_bilhete      in  date     default null,
    p_valor_passagem       in  number   default null,
    p_restricao            in  varchar2 default null
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
   Elsif p_restricao = 'INFPASS' Then
      -- Atualiza os dados das passagens
      Update pd_missao
         set pta                  = p_pta,
             emissao_bilhete      = p_emissao_bilhete,
             valor_passagem       = p_valor_passagem
       where sq_siw_solicitacao = p_chave;   
   End If;
end SP_PutPdMissao;
/
