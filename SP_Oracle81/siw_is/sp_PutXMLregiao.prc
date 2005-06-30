create or replace procedure sp_PutXMLregiao
   (p_chave    in  varchar2 default null,
    p_nome     in  varchar2,
    p_uf       in  varchar2 default null,
    p_regiao   in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   select count(*) into w_cont from is_regiao a where a.cd_regiao = p_chave;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
  
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_regiao (cd_regiao, nome, uf, cd_regiao_geografica, flag_inclusao)
      values (p_chave, p_nome, p_uf, p_regiao, sysdate);
   Else
      -- Altera registro
      update is_regiao set
         nome                 = p_nome,
         uf                   = p_uf,
         cd_regiao_geografica = p_regiao,
         flag_inclusao        = sysdate
       where cd_regiao = p_chave;
   End If;
end sp_PutXMLregiao;
/
