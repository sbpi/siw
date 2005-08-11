create or replace procedure sp_PutXMLMacro_Objetivo
   (p_chave    in  varchar2 default null,
    p_nome     in  varchar2,
    p_opcao    in  varchar2 default null,
    p_ativo    in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   If p_ativo = 'N' Then
      -- Desativa o registro
      update is_sig_macro_objetivo set ativo='N' where cd_macro = p_chave;
   Else
      select count(*) into w_cont from is_sig_macro_objetivo a where a.cd_macro = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_sig_macro_objetivo (cd_macro, nome, cd_opcao, ativo, flag_inclusao)
         values (p_chave, p_nome, p_opcao, 'S', sysdate);
      Else
         -- Altera registro
         update is_sig_macro_objetivo set
            nome          = p_nome,
            cd_opcao      = p_opcao,
            ativo         = 'S'
          where cd_macro = p_chave;
      End If;
   End If;
end sp_PutXMLMacro_Objetivo;
/
