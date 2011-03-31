create or replace procedure SP_PutLancamentoItem
   (p_operacao            in varchar2,
    p_chave               in number,
    p_chave_aux           in number   default null,
    p_sq_projeto_rubrica  in number   default null,
    p_descricao           in varchar2 default null,
    p_quantidade          in number   default null,
    p_valor_unitario      in number   default null,
    p_ordem               in number   default null,
    p_data_cotacao        in date     default null,
    p_valor_cotacao       in number   default null,
    p_solic_item          in number   default null
   ) is
   
   w_sigla     siw_menu.sigla%type;
   w_chave_aux fn_documento_item.sq_documento_item%type := p_chave_aux;
   w_reg       number(18);
begin
   If p_operacao = 'I' Then -- Inclusão
        
      select sq_documento_item.nextval into w_chave_aux from dual;
      
      insert into fn_documento_item
        (sq_documento_item,           sq_lancamento_doc,  sq_projeto_rubrica,   descricao,
         quantidade,                  valor_unitario,     ordem,                data_cotacao,
         valor_cotacao,               sq_solicitacao_item
        )
      values
        (w_chave_aux,                 p_chave,            p_sq_projeto_rubrica, p_descricao,
         p_quantidade,                p_valor_unitario,   p_ordem,              p_data_cotacao,
         coalesce(p_valor_cotacao,0), p_solic_item
        );
   Elsif p_operacao = 'A' Then -- Alteração
      update fn_documento_item
         set sq_projeto_rubrica  = p_sq_projeto_rubrica,
             descricao           = p_descricao,
             quantidade          = p_quantidade,
             valor_unitario      = p_valor_unitario,
             ordem               = p_ordem,
             data_cotacao        = p_data_cotacao,
             valor_cotacao       = coalesce(p_valor_cotacao,0),
             sq_solicitacao_item = p_solic_item
       where sq_documento_item = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      delete fn_documento_item where sq_documento_item = p_chave_aux;
   End If;
   
   If p_operacao in ('I','A') Then
      If p_sq_projeto_rubrica is not null Then
         -- Se recebeu rubrica e for única para todos os itens, atualiza o lançamento
         select count(distinct sq_projeto_rubrica) into w_reg from fn_documento_item where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave);
         If w_reg < 2 Then
            update fn_lancamento set sq_projeto_rubrica = p_sq_projeto_rubrica where sq_siw_solicitacao = p_chave;
         End If;
      Else
         select count(*) into w_reg from fn_lancamento where sq_projeto_rubrica is not null and sq_siw_solicitacao = p_chave;
         If w_reg > 0 Then 
            -- Se não recebeu rubrica mas o lancamento tem uma indicada, atribui ao item
            select sq_projeto_rubrica into w_reg from fn_lancamento where sq_siw_solicitacao = p_chave;
            If w_reg is not null Then
               update fn_documento_item set sq_projeto_rubrica = w_reg where sq_documento_item = w_chave_aux;
            End If;
         End If;
      End If;
   End If;
   
   -- Se for pagamento de diária, acumula valores no documento e na solicitação
   select c.sigla into w_sigla
     from fn_lancamento_doc            a
          inner   join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            inner join siw_menu        c on (b.sq_menu            = c.sq_menu)
    where a.sq_lancamento_doc = p_chave;
    
   If w_sigla = 'FNDVIA' or w_sigla = 'FNREVENT' Then
      -- Atualiza o documento
      update fn_lancamento_doc a
         set a.valor = coalesce((select sum(x.valor_total) from fn_documento_item x where x.sq_lancamento_doc = a.sq_lancamento_doc),0)
      where sq_lancamento_doc = p_chave;

      -- Atualiza a solicitação
      update siw_solicitacao a
         set a.valor = coalesce((select sum(x.valor) from fn_lancamento_doc x where x.sq_siw_solicitacao = a.sq_siw_solicitacao),0)
      where sq_siw_solicitacao = (select sq_siw_solicitacao from fn_lancamento_doc where sq_lancamento_doc = p_chave);
   End If;
end SP_PutLancamentoItem;
/
