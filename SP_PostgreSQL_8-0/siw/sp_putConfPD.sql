create or replace FUNCTION SP_PutConfPD
   (p_operacao                 varchar,
    p_cliente                  numeric,
    p_chave                    numeric,
    p_siw_solicitacao          numeric,
    p_rubrica                  numeric,
    p_lancamento               numeric,
    p_diaria                   varchar,
    p_hospedagem               varchar,
    p_veiculo                  varchar,
    p_seguro                   varchar,
    p_bilhete                  varchar,
    p_reembolso                varchar,
    p_ressarcimento            varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_vinculo_financeiro
        (sq_pdvinculo_financeiro,         cliente,         sq_siw_solicitacao,   sq_projeto_rubrica,   sq_tipo_lancamento, 
         diaria,                          hospedagem,      veiculo,              seguro,               bilhete,
         reembolso,                       ressarcimento)
      values
        (sq_pdvinculo_financeiro.nextval, p_cliente,       p_siw_solicitacao,    p_rubrica,            p_lancamento, 
         p_diaria,                        p_hospedagem,    p_veiculo,            p_seguro,             p_bilhete,
         p_reembolso,                     p_ressarcimento
        );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pd_vinculo_financeiro
         set sq_projeto_rubrica = p_rubrica,
             sq_tipo_lancamento = p_lancamento,
             diaria             = p_diaria,
             hospedagem         = p_hospedagem,
             veiculo            = p_veiculo,
             seguro             = p_seguro,
             bilhete            = p_bilhete,
             reembolso          = p_reembolso,
             ressarcimento      = p_ressarcimento
       where sq_pdvinculo_financeiro = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pd_vinculo_financeiro where sq_pdvinculo_financeiro = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;