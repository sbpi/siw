create or replace procedure SP_PutPAElimItem
   (p_operacao                 in  varchar2,
    p_protocolo                in  number   default null,
    p_solic                    in  number   default null,
    p_eliminacao                in  date     default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_eliminacao (sq_siw_solicitacao, protocolo) 
      (select p_solic, p_protocolo
         from dual
        where 0 = (select count(*) from pa_eliminacao where sq_siw_solicitacao = p_solic and protocolo = p_protocolo)
      );
   Elsif p_operacao = 'E' Then
      -- Tratamento para item de pedido de emprestimo
      delete pa_eliminacao where sq_siw_solicitacao = p_solic and protocolo = p_protocolo;
   Elsif p_operacao = 'V' Then
      -- Registra a data de devolução do item
      update pa_eliminacao
          set eliminacao = p_eliminacao
      where sq_siw_solicitacao = p_solic 
        and protocolo          = p_protocolo;
   End If;
end SP_PutPAElimItem;
/
