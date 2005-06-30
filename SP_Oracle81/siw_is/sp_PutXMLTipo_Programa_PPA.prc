create or replace procedure sp_PutXMLTipo_Programa_PPA
   (p_chave    in  number   default null,
    p_nome     in  varchar2,
    p_ativo    in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   If p_ativo = 'N' Then
      -- Desativa o registro
      update is_ppa_tipo_programa set ativo='N' where cd_Tipo_programa = p_chave;
   Else
      select count(*) into w_cont from is_ppa_tipo_programa a where a.cd_tipo_programa = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_ppa_tipo_programa (cd_tipo_programa, nome, ativo, flag_inclusao)
         values (p_chave, p_nome, 'S', sysdate);
      Else
         -- Altera registro
         update is_ppa_tipo_programa set
            nome          = p_nome,
            ativo         = 'S',
            flag_inclusao = sysdate
          where cd_tipo_programa = p_chave;
      End If;
   End If;
end sp_PutXMLTipo_Programa_PPA;
/
