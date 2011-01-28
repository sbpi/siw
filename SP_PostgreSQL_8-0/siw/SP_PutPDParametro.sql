create or replace FUNCTION SP_PutPDParametro
   (p_cliente                   numeric,
    p_sequencial                numeric,
    p_ano_corrente              numeric,
    p_prefixo                   varchar,
    p_sufixo                    varchar,
    p_dias_antecedencia         numeric,
    p_dias_anteced_int          numeric,
    p_dias_prest_contas         numeric,
    p_limite_unidade            varchar,
    p_cadastrador_geral         varchar
   ) RETURNS VOID AS $$
DECLARE
   
   p_operacao varchar(1);
   w_existe   numeric(18);
   
BEGIN
   -- Verifica se operação de inclusão ou alteração
   select count(*) into w_existe from pd_parametro a where a.cliente = p_cliente;
   If w_existe > 0 Then
      p_operacao := 'A';
   Else
      p_operacao := 'I';
   End If;
   
   -- Grava os parametros do módulo de viagens do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_parametro
         (cliente,   sequencial,   ano_corrente,     prefixo,   sufixo,   dias_antecedencia,   dias_antecedencia_int,
          dias_prestacao_contas,   limite_unidade,   cadastrador_geral)
      values
         (p_cliente, p_sequencial, p_ano_corrente,   p_prefixo, p_sufixo, p_dias_antecedencia, p_dias_anteced_int,
          p_dias_prest_contas,     p_limite_unidade, p_cadastrador_geral);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pd_parametro
         set sequencial            = p_sequencial,
             ano_corrente          = p_ano_corrente,
             prefixo               = p_prefixo,
             sufixo                = p_sufixo,
             dias_antecedencia     = p_dias_antecedencia,
             dias_antecedencia_int = p_dias_anteced_int,
             dias_prestacao_contas = p_dias_prest_contas,
             limite_unidade        = p_limite_unidade,
             cadastrador_geral     = p_cadastrador_geral
       where cliente = p_cliente;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;