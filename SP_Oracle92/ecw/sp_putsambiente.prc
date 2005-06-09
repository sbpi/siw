create or replace procedure SP_PutSAmbiente
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_ds_ambiente              in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_ambiente (co_seq_ambiente, ds_ambiente)
         (select co_seq_ambiente.nextval,
                 trim(upper(p_ds_ambiente))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_ambiente set
         ds_ambiente          = trim(upper(p_ds_ambiente))
      where co_seq_ambiente   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_ambiente where co_seq_ambiente = p_chave;
   End If;
end SP_PutSAmbiente;
/

