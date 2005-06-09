create or replace procedure SP_PutMetaMensal_IS
   (p_operacao            in varchar2,
    p_chave               in number,
    p_realizado           in number   default null,
    p_revisado            in number   default null,
    p_referencia          in date     default null,
    p_cliente             in number   default null
   ) is
begin   
   if p_operacao = 'E' Then
      -- Apaga todos os registros para que seja feita a atualização
      delete is_meta_execucao where sq_meta = p_chave;
   Else
      -- Insere registro na tabela de meses da meta
      Insert Into is_meta_execucao
         ( sq_meta, referencia,   realizado, revisado, cliente)
         Values
         ( p_chave,          last_day(p_referencia), p_realizado,  p_revisado, p_cliente);
   End If;
end SP_PutMetaMensal_IS;
/

