create or replace FUNCTION SP_PutPAEmpItem
   (p_operacao                  varchar,
    p_protocolo                 numeric,
    p_solic                     numeric,
    p_devolucao                 date     
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_emprestimo_item (sq_siw_solicitacao, protocolo) 
      (select p_solic, p_protocolo
         from dual
        where 0 = (select count(*) from pa_emprestimo_item where sq_siw_solicitacao = p_solic and protocolo = p_protocolo)
      );
   Elsif p_operacao = 'E' Then
      -- Tratamento para item de pedido de emprestimo
      DELETE FROM pa_emprestimo_item where sq_siw_solicitacao = p_solic and protocolo = p_protocolo;
   Elsif p_operacao = 'V' Then
      -- Registra a data de devolução do item
      update pa_emprestimo_item
          set devolucao = p_devolucao
      where sq_siw_solicitacao = p_solic 
        and protocolo          = p_protocolo;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;