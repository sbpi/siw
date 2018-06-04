create or replace procedure SP_PutSolicClassif
   (p_usuario                  in  number,
    p_observacao               in  varchar2,
    p_rubrica                  in  number   default null,
    p_fonte                    in  number   default null,
    p_lancamento               in  number,
    p_item                     in  number   default null
   ) is
begin
   -- Altera o lançamento financeiro
   update fn_lancamento
      set sq_projeto_rubrica = nvl(p_rubrica, sq_projeto_rubrica),
          sq_solic_apoio     = nvl(p_fonte, sq_solic_apoio)
   where sq_siw_solicitacao = p_lancamento;
   
   -- Altera o item apenas se receber sua chave primária
   If p_item is not null Then
      update fn_documento_item
         set sq_projeto_rubrica = nvl(p_rubrica, sq_projeto_rubrica),
             sq_solic_apoio     = nvl(p_fonte, sq_solic_apoio)
      where sq_documento_item = p_item;

      -- Há lançamentos com item(ns). Nesse caso, cada item precisa do registro da alteração
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
          sq_siw_tramite,            data,                 devolucao, 
          observacao
         )
      (select 
          sq_siw_solic_log.nextval,  c.sq_siw_solicitacao, p_usuario,
          c.sq_siw_tramite,          sysdate,              'N',
          'Alteração de rubrica e/ou fonte no item '||a.ordem||'. Motivo: '||p_observacao
         from fn_documento_item              a
              inner   join fn_lancamento_doc b on a.sq_lancamento_doc  = b.sq_lancamento_doc
                inner join siw_solicitacao   c on b.sq_siw_solicitacao = c.sq_siw_solicitacao
        where a.sq_documento_item = p_item
      );
   Else
      -- Há lançamentos sem item. Nesse caso, registra a alteração no próprio lançamento
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
          sq_siw_tramite,            data,                 devolucao, 
          observacao
         )
      (select 
          sq_siw_solic_log.nextval,  c.sq_siw_solicitacao, p_usuario,
          c.sq_siw_tramite,          sysdate,              'N',
          'Alteração de rubrica e/ou fonte. Motivo: '||p_observacao
         from siw_solicitacao   c
        where c.sq_siw_solicitacao = p_lancamento
      );
   End If;
end SP_PutSolicClassif;
/
