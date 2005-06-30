create or replace procedure sp_PutXMLSubfuncao
   (p_chave    in  number   default null,
    p_funcao   in  varchar2,
    p_desc     in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   select count(*) into w_cont from is_ppa_subfuncao a where a.cd_subfuncao = p_chave;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
 
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_ppa_subfuncao (cd_subfuncao, cd_funcao, descricao, flag_inclusao)
      values (p_chave, p_funcao, p_desc, sysdate);
   Else
      -- Altera registro
      update is_ppa_subfuncao set
         cd_funcao     = p_funcao,
         descricao     = p_desc,
         flag_inclusao = sysdate
       where cd_subfuncao = p_chave;
   End If;
end sp_PutXMLSubfuncao;
/
