create or replace procedure SP_PutPDUsuario
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_chave                    in  number
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_usuario
             (cliente,   sq_pessoa)
      (select p_cliente, p_chave from dual);
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pd_usuario where sq_pessoa = p_chave;
   End If;
end SP_PutPDUsuario;
/
