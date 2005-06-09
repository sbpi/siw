create or replace procedure sp_PutHorizonte_IS
   (p_operacao in  varchar2             ,
    p_chave    in  number   default null,
    p_cliente  in  number   default null,
    p_nome     in  varchar2             ,
    p_ativo    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into is_horizonte (sq_horizonte, cliente, nome, ativo)
      (select sq_horizonte.nextval, p_cliente, p_nome,  p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update is_horizonte
         set 
             cliente      = p_cliente,
             nome         = p_nome,
             ativo        = p_ativo
       where sq_horizonte = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete is_horizonte
       where sq_horizonte = p_chave;
   End If;
end sp_PutHorizonte_IS;
/

