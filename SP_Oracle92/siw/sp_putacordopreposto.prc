create or replace procedure SP_PutAcordoPreposto
   ( p_operacao               in varchar2,
     p_restricao              in varchar2,
     p_chave                  in number    default null,
     p_sq_acordo_outra_parte  in number    default null,
     p_sq_pessoa              in number    default null,
     p_cliente                in number    default null,
     p_cargo                  in varchar2  default null
   ) is
   
   w_existe          number(18);
   w_chave_pessoa    number(18) := Nvl(p_sq_pessoa,0);
   w_outra_parte1    number(18);
   w_outra_parte2    number(18);
   w_chave           number(18);
   w_preposto        number(18);   
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ac_acordo_preposto
         (sq_siw_solicitacao, sq_acordo_outra_parte,   sq_pessoa,      cargo)
      values
         (p_chave,            p_sq_acordo_outra_parte, w_chave_pessoa, p_cargo);
      
      select nvl(preposto,0) into w_existe from ac_acordo where sq_siw_solicitacao = p_chave;
      If w_existe = 0 Then
         select outra_parte into w_outra_parte1 from ac_acordo             where sq_siw_solicitacao = p_chave;
         select outra_parte into w_outra_parte2 from ac_acordo_outra_parte where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
         If w_outra_parte1 = w_outra_parte2 Then
           update ac_acordo set preposto = w_chave_pessoa where sq_siw_solicitacao = p_chave;
         End If;
      End If;      
   Elsif  p_operacao = 'A' Then 
      -- Altera cargo do PREPOSTO
      update ac_acordo_preposto
         set cargo = p_cargo
      where sq_pessoa             = w_chave_pessoa
        and sq_acordo_outra_parte = p_sq_acordo_outra_parte
        and sq_siw_solicitacao    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      select sq_siw_solicitacao into w_chave 
        from ac_acordo_outra_parte 
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
       
      select count(*) into w_existe from ac_acordo_preposto where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      
      delete ac_acordo_preposto  
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte
         and sq_pessoa             = w_chave_pessoa;             
      
      If w_existe > 1 Then
        select sq_pessoa into w_preposto 
          from ac_acordo_preposto 
         where sq_acordo_outra_parte = p_sq_acordo_outra_parte
           and rownum = 1;
         
        update ac_acordo set preposto = w_preposto
         where sq_siw_solicitacao = w_chave
           and preposto           = w_chave_pessoa;
      Else
        update ac_acordo set preposto = null         
         where sq_siw_solicitacao = w_chave
           and preposto           = w_chave_pessoa;
      End If;      
   End If;
end SP_PutAcordoPreposto;
/
