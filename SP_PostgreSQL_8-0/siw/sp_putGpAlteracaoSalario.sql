create or replace FUNCTION sp_putGpAlteracaoSalario
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_chave_aux                 numeric,
    p_data_alteracao            date,
    p_novo_valor                numeric,
    p_funcao                    varchar,
    p_motivo                    varchar,
    restricao                   varchar    
   ) RETURNS VOID AS $$   
DECLARE
BEGIN
  -- Grava os parametros do módulo de recursos humanos do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_alteracao_salario(sq_alteracao_salario, sq_contrato_colaborador,data_alteracao,
                                       novo_valor,             funcao, motivo)
         (select nextVal('sq_alteracao_salario'),p_chave, p_data_alteracao, p_novo_valor, p_funcao, p_motivo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
    update gp_alteracao_salario
       set data_alteracao       = p_data_alteracao,
           novo_valor           = p_novo_valor,
           funcao               = p_funcao,
           motivo               = p_motivo
     where sq_alteracao_salario = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
    DELETE FROM gp_alteracao_salario
     where sq_alteracao_salario = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;