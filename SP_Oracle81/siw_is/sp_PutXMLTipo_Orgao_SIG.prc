create or replace procedure sp_PutXMLTipo_Orgao_SIG
   (p_chave    in  varchar2 default null,
    p_nome     in  varchar2,
    p_ativo    in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   If p_ativo = 'N' Then
      -- Desativa o registro no PPA e no SIGPLAN
      update is_ppa_tipo_orgao set ativo='N' where cd_tipo_orgao = p_chave;
      update is_sig_tipo_orgao set ativo='N' where cd_tipo_orgao = p_chave;
   Else
      -- Verifica a tabela no PPA
      select count(*) into w_cont from is_ppa_tipo_orgao a where a.cd_tipo_orgao = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_ppa_tipo_orgao (cd_tipo_orgao, nome, ativo, flag_inclusao)
         values (p_chave, p_nome, 'S', sysdate);
      Else
         -- Altera registro
         update is_ppa_tipo_orgao set
            nome          = p_nome,
            ativo         = 'S',
            flag_inclusao = sysdate
          where cd_tipo_orgao = p_chave;
      End If;

      -- Verifica a tabela no SIGPLAN
      select count(*) into w_cont from is_sig_tipo_orgao a where a.cd_tipo_orgao = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_sig_tipo_orgao (cd_tipo_orgao, nome, ativo, flag_inclusao)
         values (p_chave, p_nome, 'S', sysdate);
      Else
         -- Altera registro
         update is_sig_tipo_orgao set
            nome          = p_nome,
            ativo         = 'S'
          where cd_tipo_orgao = p_chave;
      End If;
   End If;
end sp_PutXMLTipo_Orgao_SIG;
/
