create or replace procedure SP_PutConfPD
   (p_operacao                 in varchar2,
    p_cliente                  in number,
    p_chave                    in number   default null,
    p_siw_solicitacao          in number   default null,
    p_rubrica                  in number   default null,
    p_lancamento               in number  default null,
    p_diaria                   in varchar2 default null,
    p_hospedagem               in varchar2 default null,
    p_veiculo                  in varchar2 default null,
    p_seguro                   in varchar2 default null,
    p_bilhete                  in varchar2 default null,
    p_reembolso                in varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_vinculo_financeiro
        (sq_pdvinculo_financeiro,         cliente,      sq_siw_solicitacao,   sq_projeto_rubrica,   sq_tipo_lancamento, 
         diaria,                          hospedagem,   veiculo,              seguro,               bilhete,
         reembolso)
      values
        (sq_pdvinculo_financeiro.nextval, p_cliente,    p_siw_solicitacao,    p_rubrica,            p_lancamento, 
         p_diaria,                        p_hospedagem, p_veiculo,            p_seguro,             p_bilhete,
         p_reembolso
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
             reembolso          = p_reembolso
       where sq_pdvinculo_financeiro = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pd_vinculo_financeiro where sq_pdvinculo_financeiro = p_chave;
   End If;
end SP_PutConfPD;
/
