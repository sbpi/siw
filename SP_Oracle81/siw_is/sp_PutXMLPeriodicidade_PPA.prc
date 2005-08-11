create or replace procedure sp_PutXMLPeriodicidade_PPA
   (p_chave    in  number   default null,
    p_nome     in  varchar2,
    p_ativo    in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   If p_ativo = 'N' Then
      -- Desativa o registro
      update is_ppa_periodicidade set ativo='N' where cd_Periodicidade = p_chave;
   Else
      select count(*) into w_cont from is_ppa_periodicidade a where a.cd_Periodicidade = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_ppa_periodicidade (cd_Periodicidade, nome, ativo, flag_inclusao)
         values (p_chave, p_nome, 'S', sysdate);
      Else
         -- Altera registro
         update is_ppa_periodicidade set
            nome          = p_nome,
            ativo         = 'S'
          where cd_Periodicidade = p_chave;
      End If;
   End If;
end sp_PutXMLPeriodicidade_PPA;
/
