create or replace procedure sp_PutXMLNatureza
   (p_chave    in  number   default null,
    p_nome     in  varchar2,
    p_desc     in  varchar2 default null,
    p_ativo    in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   If p_ativo = 'N' Then
      -- Desativa o registro
      update is_ppa_natureza set ativo='N' where cd_natureza = p_chave;
   Else
      select count(*) into w_cont from is_ppa_natureza a where a.cd_natureza = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_ppa_natureza (cd_natureza, nome, descricao, ativo, flag_inclusao)
         values (p_chave, p_nome, p_desc, 'S', sysdate);
      Else
         -- Altera registro
         update is_ppa_natureza set
            nome          = p_nome,
            descricao     = p_desc,
            ativo         = 'S',
            flag_inclusao = sysdate
          where cd_natureza = p_chave;
      End If;
   End If;
end sp_PutXMLNatureza;
/
