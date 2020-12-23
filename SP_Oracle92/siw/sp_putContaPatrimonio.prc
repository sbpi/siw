create or replace procedure SP_PutContaPatrimonio
   (p_usuario             in number,
    p_permanente          in number,
    p_conta_patrimonial   in varchar2 default null,
    p_conta_depreciacao   in varchar2 default null
   ) is
   
   w_reg number(10);
   
begin

   -- Grava as informações somente se alguma das contas informadas for diferente do que já está gravado.
      select count(*)
        into w_reg
        from mt_permanente
       where sq_permanente            = p_permanente
         and (nvl(cc_patrimonial,'-') <> nvl(p_conta_patrimonial,'-') or 
              nvl(cc_depreciacao,'-') <> nvl(p_conta_depreciacao,'-')
             );
   
      If w_reg > 0 Then
         update mt_permanente 
            set cc_patrimonial = p_conta_patrimonial, 
                cc_depreciacao = p_conta_depreciacao,
                cc_pessoa      = p_usuario,
                cc_data        = sysdate 
         where sq_permanente   = p_permanente;
      End If;
end SP_PutContaPatrimonio;
/
