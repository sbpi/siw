create or replace procedure sp_PutXMLUnidade_Medida_SIG
   (p_chave    in  number   default null,
    p_nome     in  varchar2,
    p_tipo     in  varchar2 default null,
    p_ativo    in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   If p_ativo = 'N' Then
      -- Desativa o registro
      update is_sig_unidade_medida set ativo='N' where cd_unidade_medida = p_chave;
   Else
      select count(*) into w_cont from is_sig_unidade_medida a where a.cd_unidade_medida = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_sig_unidade_medida (cd_unidade_medida, nome, tipo, ativo, flag_inclusao)
         values (p_chave, p_nome, p_tipo, 'S', sysdate);
      Else
         -- Altera registro
         update is_sig_unidade_medida set
            nome          = p_nome,
            tipo          = p_tipo,
            ativo         = 'S',
            flag_inclusao = sysdate
          where cd_unidade_medida = p_chave;
      End If;
   End If;
end sp_PutXMLUnidade_Medida_SIG;
/
