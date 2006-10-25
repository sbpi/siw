create or replace procedure sp_putopcaoestrat_IS
   (p_operacao  in  varchar2             ,
    p_chave     in  varchar2             ,
    p_nome      in  varchar2             ,
    p_ativo     in  varchar2 default null,
    p_chave_aux in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into is_sig_opcao_estrat  (cd_opcao, nome, ativo)
      (select p_chave,  p_nome,  p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update is_sig_opcao_estrat
         set 
             cd_opcao     = p_chave,
             nome         = p_nome,
             ativo        = p_ativo
       where cd_opcao     = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete is_sig_opcao_estrat
       where cd_opcao = p_chave;
   End If;

end sp_putopcaoestrat_IS;
/
