create or replace procedure SP_PutAcordoFornecimento
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_chave_aux                in  number   default null,
    p_ordem_fornecimento       in  varchar2 default null,
    p_numero                   in  varchar2 default null,
    p_local_entrega            in  varchar2 default null,
    p_agendamento              in  varchar2 default null,
    p_mail                     in  varchar2 default null,
    p_numero_processo          in  varchar2 default null,
    p_nota_empenho             in  varchar2 default null,
    p_valor_empenho            in  number   default null,
    p_data_prevista            in  date     default null,
    p_autorizador_nome         in  varchar2 default null,
    p_autorizador_funcao       in  varchar2 default null,
    p_solicitante              in  varchar2 default null,
    p_responsavel_nome         in  varchar2 default null,
    p_responsavel_funcao       in  varchar2 default null,
    p_responsavel_rg           in  varchar2 default null,
    p_responsavel_data         in  date     default null,
    p_situacao                 in  varchar2 default null,
    p_sq_item                  in  number   default null,
    p_quantidade               in  number   default null,
    p_valor_item               in  number   default null,
    p_chave_nova               out number
   ) is
   w_chave   number(18);
begin
   If p_operacao = 'I' Then
      select sq_autorizacao_fornecimento.nextval into w_chave from dual;

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
      delete cl_item_autorizacao where sq_autorizacao_fornecimento = p_chave;
      delete cl_autorizacao_fornecimento where sq_autorizacao_fornecimento = p_chave;
   Elsif p_operacao = 'ITEM' Then
      insert into cl_item_autorizacao
        (sq_item_autorizacao, sq_autorizacao_fornecimento, sq_solicitacao_item, quantidade, valor_unitario)
      values
        (sq_item_autorizacao.nextval, p_chave, p_sq_item, p_quantidade, p_valor_item);
   Elsif p_operacao = 'EXCLUIITEM' Then
      delete cl_item_autorizacao where sq_autorizacao_fornecimento = coalesce(p_chave,0);
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutAcordoFornecimento;
/
