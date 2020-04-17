create or replace procedure SP_PutContaContabil
   (p_usuario             in number,
    p_solicitacao         in number,
    p_conta_debito        in varchar2 default null,
    p_conta_credito       in varchar2 default null
   ) is
   
   w_reg number(10);
   
begin

   -- Grava as informações somente se alguma das contas informadas for diferente do que já está gravado.
      select count(*)
        into w_reg
        from fn_lancamento
       where sq_siw_solicitacao   = p_solicitacao
         and (nvl(cc_debito,'-')  <> nvl(p_conta_debito,'-') or 
              nvl(cc_credito,'-') <> nvl(p_conta_credito,'-')
             );
   
      If w_reg > 0 Then
         update fn_lancamento 
            set cc_debito  = p_conta_debito, 
                cc_credito = p_conta_credito,
                cc_pessoa = p_usuario,
                cc_data    = sysdate 
         where sq_siw_solicitacao = p_solicitacao;
      End If;
end SP_PutContaContabil;
/
