create or replace procedure SP_PutSOrEscola
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_ds_origem_escola         in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_origem_escola (co_origem_escola, ds_origem_escola)
         (select co_origem_escola.nextval,
                 trim(upper(p_ds_origem_escola))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_origem_escola set
         ds_origem_escola      = trim(upper(p_ds_origem_escola))
      where co_origem_escola   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_origem_escola where co_origem_escola = p_chave;
   End If;
end SP_PutSOrEscola;
/

