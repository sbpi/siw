create or replace FUNCTION SP_PutFNParametro
   (p_cliente                   numeric,
    p_sequencial                numeric,
    p_ano_corrente              numeric,
    p_prefixo                   varchar,
    p_sufixo                    varchar,
    p_fundo_valor              numeric,
    p_fundo_qtd                numeric,
    p_fundo_util               numeric,
    p_fundo_contas             numeric,
    p_fundo_data               varchar,
    p_texto_devolucao           varchar 
   ) RETURNS VOID AS $$
DECLARE
   
   p_operacao     varchar(1);
   w_existe       numeric(18);
   w_sequencial   numeric(18) := p_sequencial;
   
BEGIN
   -- Verifica se operação de inclusão ou alteração
   select count(*) into w_existe from fn_parametro a where a.cliente = p_cliente;
   If w_existe > 0 Then
      p_operacao := 'A';
   Else
      p_operacao := 'I';
   End If;
   
   -- Grava os parametros do módulo de viagens do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_parametro
         (cliente,   sequencial,   ano_corrente,   prefixo,   sufixo,   fundo_fixo_valor,  fundo_fixo_qtd, fundo_fixo_dias_utilizacao, fundo_fixo_dias_contas, 
          fundo_fixo_data_contas, texto_devolucao)
      values
         (p_cliente, p_sequencial, p_ano_corrente, p_prefixo, p_sufixo, p_fundo_valor,     p_fundo_qtd,    p_fundo_util,               p_fundo_contas,
          p_fundo_data,            p_texto_devolucao
         );
   Elsif p_operacao = 'A' Then
      -- Verifica o valor atual no banco
      select sequencial into w_sequencial from fn_parametro where cliente = p_cliente;
      If w_sequencial < p_sequencial Then w_sequencial := p_sequencial; End If;
      -- Altera registro
      update fn_parametro
         set sequencial                 = w_sequencial,
             ano_corrente               = p_ano_corrente,
             prefixo                    = p_prefixo,
             sufixo                     = p_sufixo,
             fundo_fixo_valor           = p_fundo_valor,
             fundo_fixo_qtd             = p_fundo_qtd,
             fundo_fixo_dias_utilizacao = p_fundo_util,
             fundo_fixo_dias_contas     = p_fundo_contas,
             fundo_fixo_data_contas     = p_fundo_data,
             texto_devolucao            = p_texto_devolucao
       where cliente = p_cliente;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;