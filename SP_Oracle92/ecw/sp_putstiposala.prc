create or replace procedure SP_PutSTipoSala
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_ds_tipo_sala         in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_tipo_sala (co_tipo_sala, ds_tipo_sala)
         (select co_tipo_sala.nextval,
                 trim(upper(p_ds_tipo_sala))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_tipo_sala set
         ds_tipo_sala      = trim(upper(p_ds_tipo_sala))
      where co_tipo_sala   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_tipo_sala where co_tipo_sala = p_chave;
   End If;
end SP_PutSTipoSala;
/

