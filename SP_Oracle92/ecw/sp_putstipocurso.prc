create or replace procedure SP_PutSTipoCurso
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_sg_tipo_curso            in  varchar2,
    p_ds_tipo_curso            in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_tipo_curso (co_tipo_curso, sg_tipo_curso, ds_tipo_curso)
         (select co_tipo_curso.nextval,
                 trim(upper(p_sg_tipo_curso)),
                 trim(upper(p_ds_tipo_curso))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_tipo_curso set
         sg_tipo_curso          = trim(upper(p_sg_tipo_curso)),
         ds_tipo_curso          = trim(upper(p_ds_tipo_curso))
      where co_tipo_curso   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_tipo_curso where co_tipo_curso = p_chave;
   End If;
end SP_PutSTipoCurso;
/

