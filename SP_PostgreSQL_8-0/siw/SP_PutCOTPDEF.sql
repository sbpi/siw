create or replace function SP_PutCOTPDEF
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_sq_grupo_deficiencia     numeric,
    p_codigo                   varchar,
    p_nome                     varchar,
    p_descricao                varchar,
    p_ativo                    varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_deficiencia (sq_deficiencia, sq_grupo_defic, codigo, nome, descricao, ativo)
         (select nextval('sq_deficiencia'),
                 p_sq_grupo_deficiencia,
                 trim(p_codigo),
                 trim(p_nome),
                 trim(p_descricao),
                 p_ativo
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_deficiencia set
         sq_grupo_defic       = p_sq_grupo_deficiencia,
         codigo               = trim(p_codigo),
         nome                 = trim(p_nome),
         descricao            = trim(p_descricao),
         ativo                = p_ativo
      where sq_deficiencia    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_deficiencia where sq_deficiencia = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;