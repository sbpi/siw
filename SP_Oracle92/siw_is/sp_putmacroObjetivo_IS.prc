create or replace procedure sp_putmacroObjetivo_IS
   (p_operacao  in  varchar2             ,
    p_chave     in  varchar2             ,
    p_opcao     in  varchar2             ,
    p_nome      in  varchar2             ,
    p_ativo     in  varchar2 default null,
    p_chave_aux in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into is_sig_macro_objetivo  (cd_macro, cd_opcao, nome, ativo)
      (select p_chave, p_opcao,  p_nome,  p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update is_sig_macro_objetivo
         set 
             cd_macro     = p_chave,
             cd_opcao     = p_opcao,
             nome         = p_nome,
             ativo        = p_ativo
       where cd_macro     = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete is_sig_macro_objetivo
       where cd_macro = p_chave;
   End If;

end sp_putmacroObjetivo_IS;
/
