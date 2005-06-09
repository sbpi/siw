create or replace procedure SP_PutSTipoDisc
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_sg_disciplina            in  char,
    p_ds_tipo_disciplina       in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_tipo_disciplina (co_tipo_disciplina, sg_disciplina, ds_tipo_disciplina)
         (select co_tipo_disciplina.nextval,
                 trim(upper(p_sg_disciplina)),
                 trim(upper(p_ds_tipo_disciplina))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_tipo_disciplina set
         sg_disciplina          = trim(upper(p_sg_disciplina)),
         ds_tipo_disciplina     = trim(upper(p_ds_tipo_disciplina))
      where co_tipo_disciplina  = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_tipo_disciplina where co_tipo_disciplina = p_chave;
   End If;
end SP_PutSTipoDisc;
/

