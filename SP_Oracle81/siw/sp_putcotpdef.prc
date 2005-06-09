create or replace procedure SP_PutCOTPDEF
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_sq_grupo_deficiencia     in  number default null,
    p_codigo                   in  varchar2,
    p_nome                     in  varchar2,
    p_descricao                in  varchar2,
    p_ativo                    in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_deficiencia (sq_deficiencia, sq_grupo_defic, codigo, nome, descricao, ativo)
         (select sq_deficiencia.nextval,
                 p_sq_grupo_deficiencia,
                 trim(p_codigo),
                 trim(p_nome),
                 trim(p_descricao),
                 p_ativo
            from dual
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
      delete co_deficiencia where sq_deficiencia = p_chave;
   End If;
end SP_PutCOTPDEF;
/

