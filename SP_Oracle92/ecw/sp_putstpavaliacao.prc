create or replace procedure SP_PutSTPAvaliacao
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_ds_tipo_avaliacao        in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_tipo_avaliacao (co_tipo_avaliacao, ds_tipo_avaliacao)
         (select co_tipo_avaliacao.nextval,
                 trim(upper(p_ds_tipo_avaliacao))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_tipo_avaliacao set
         ds_tipo_avaliacao     = trim(upper(p_ds_tipo_avaliacao))
      where co_tipo_avaliacao   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_tipo_avaliacao where co_tipo_avaliacao = p_chave;
   End If;
end SP_PutSTPAvaliacao;
/

