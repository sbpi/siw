create or replace procedure sp_PutXMLUnidade_Medida_PPA
   (p_chave    in  number   default null,
    p_nome     in  varchar2,
    p_ativo    in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   If p_ativo = 'N' Then
      -- Desativa o registro
      update is_ppa_unidade_medida set ativo='N' where cd_unidade_medida = p_chave;
   Else
      select count(*) into w_cont from is_ppa_unidade_medida a where a.cd_unidade_medida = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_ppa_unidade_medida (cd_unidade_medida, nome, ativo, flag_inclusao)
         values (p_chave, Nvl(p_nome,'N�o informado no arquivo XML'), 'S', sysdate);
      Else
         -- Altera registro
         update is_ppa_unidade_medida set
            nome          = Nvl(p_nome,'N�o informado no arquivo XML'),
            ativo         = 'S'
          where cd_unidade_medida = p_chave;
      End If;
   End If;
end sp_PutXMLUnidade_Medida_PPA;
/
