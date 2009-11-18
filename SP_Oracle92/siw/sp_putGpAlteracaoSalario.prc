create or replace procedure sp_putGpAlteracaoSalario
   (p_operacao                 in  varchar2,
    p_chave                    in  number,
    p_chave_aux                in  number default null,
    p_data_alteracao           in  date,
    p_novo_valor               in  number,
    p_funcao                   in  varchar2,
    p_motivo                   in  varchar2,
    restricao                  in  varchar2 default null   
   ) is   
begin
  -- Grava os parametros do módulo de recursos humanos do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_alteracao_salario(sq_alteracao_salario, sq_contrato_colaborador,data_alteracao,
                                       novo_valor,             funcao, motivo)
         (select sq_alteracao_salario.nextval,p_chave, p_data_alteracao, p_novo_valor, p_funcao, p_motivo from dual);
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
    delete gp_alteracao_salario
     where sq_alteracao_salario = p_chave_aux;
   End If;
end sp_putGpAlteracaoSalario;
/
