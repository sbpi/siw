create or replace FUNCTION SP_PutConfCL
   (p_operacao                 varchar,
    p_cliente                  numeric,
    p_menu                     numeric,
    p_siw_solicitacao          numeric,
    p_chave                    numeric,
    p_rubrica                  numeric,
    p_lancamento               numeric,
    p_consumo                  varchar,
    p_permanente               varchar,
    p_servico                  varchar,
    p_outros                   varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into cl_vinculo_financeiro
        (sq_clvinculo_financeiro,         cliente,      sq_siw_solicitacao,   sq_projeto_rubrica,   sq_tipo_lancamento, 
         consumo,                         permanente,   servico,              outros,               sq_menu)
      values
        (sq_clvinculo_financeiro.nextval, p_cliente,    p_siw_solicitacao,    p_rubrica,            p_lancamento, 
         p_consumo,                       p_permanente, p_servico,            p_outros,             p_menu
        );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update cl_vinculo_financeiro
         set sq_projeto_rubrica = p_rubrica,
             sq_tipo_lancamento = p_lancamento,
             consumo            = p_consumo,
             permanente         = p_permanente,
             servico            = p_servico,
             outros             = p_outros
       where sq_clvinculo_financeiro = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM cl_vinculo_financeiro where sq_clvinculo_financeiro = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;