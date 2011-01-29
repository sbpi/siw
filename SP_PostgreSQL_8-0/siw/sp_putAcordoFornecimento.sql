create or replace FUNCTION SP_PutAcordoFornecimento
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_chave_aux                 numeric,
    p_ordem_fornecimento        varchar,
    p_numero                    varchar,
    p_local_entrega             varchar,
    p_agendamento               varchar,
    p_mail                      varchar,
    p_numero_processo           varchar,
    p_nota_empenho              varchar,
    p_valor_empenho             numeric,
    p_data_prevista             date,
    p_autorizador_nome          varchar,
    p_autorizador_funcao        varchar,
    p_solicitante               varchar,
    p_responsavel_nome          varchar,
    p_responsavel_funcao        varchar,
    p_responsavel_rg            varchar,
    p_responsavel_data          date,
    p_situacao                  varchar,
    p_sq_item                   numeric,
    p_quantidade                numeric,
    p_valor_item                numeric,
    p_chave_nova               numeric
   ) RETURNS VOID AS $$
DECLARE
   w_chave   numeric(18);
BEGIN
   If p_operacao = 'I' Then
      select nextVal('sq_autorizacao_fornecimento') into w_chave;

      -- Insere registro
      insert into cl_autorizacao_fornecimento
        (sq_autorizacao_fornecimento, sq_siw_solicitacao, numero, local_entrega, agendamento, mail, processo, nota_empenho, valor_empenho, 
         data_prevista, autorizador_nome, autorizador_funcao, solicitante, responsavel_nome, responsavel_funcao, responsavel_rg, responsavel_data, 
         ordem_fornecimento, situacao)
      values
        (w_chave, p_chave_aux, p_numero, p_local_entrega, p_agendamento, p_mail, p_numero_processo, p_nota_empenho, p_valor_empenho, 
         p_data_prevista, p_autorizador_nome, p_autorizador_funcao, p_solicitante, p_responsavel_nome, p_responsavel_funcao, p_responsavel_rg, 
         p_responsavel_data, p_ordem_fornecimento, p_situacao);

   Elsif p_operacao = 'A' Then
      -- Altera registro
      update cl_autorizacao_fornecimento
         set numero              = p_numero,
             local_entrega       = p_local_entrega,
             agendamento         = p_agendamento,
             mail                = p_mail,
             processo            = p_numero_processo,
             nota_empenho        = p_nota_empenho,
             valor_empenho       = p_valor_empenho,
             data_prevista       = p_data_prevista,
             autorizador_nome    = p_autorizador_nome,
             autorizador_funcao  = p_autorizador_funcao,
             solicitante         = p_solicitante,
             responsavel_nome    = p_responsavel_nome,
             responsavel_funcao  = p_responsavel_funcao,
             responsavel_rg      = p_responsavel_rg,
             responsavel_data    = p_responsavel_data,
             ordem_fornecimento  = p_ordem_fornecimento,
             situacao            = p_situacao
       where sq_autorizacao_fornecimento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM cl_item_autorizacao where sq_autorizacao_fornecimento = p_chave;
      DELETE FROM cl_autorizacao_fornecimento where sq_autorizacao_fornecimento = p_chave;
   Elsif p_operacao = 'ITEM' Then
      insert into cl_item_autorizacao
        (sq_item_autorizacao, sq_autorizacao_fornecimento, sq_solicitacao_item, quantidade, valor_unitario)
      values
        (nextVal('sq_item_autorizacao'), p_chave, p_sq_item, p_quantidade, p_valor_item);
   Elsif p_operacao = 'EXCLUIITEM' Then
      DELETE FROM cl_item_autorizacao where sq_autorizacao_fornecimento = coalesce(p_chave,0);
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;