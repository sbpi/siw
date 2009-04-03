create or replace procedure SP_PutCOGRDEF
   (p_operacao          in  varchar2,
    p_chave             in  number default null,
    p_nome              in  varchar2,
    p_codigo_externo    in  varchar2,
    p_ativo             in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_grupo_defic (sq_grupo_defic, nome, codigo_externo, ativo)  
         (select sq_grupo_deficiencia.nextval,  
                 trim(p_nome), trim(p_codigo_externo),
                 p_ativo
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_grupo_defic set 
         nome              = trim(p_nome),
         codigo_externo    = trim(p_codigo_externo),
         ativo             = p_ativo
      where sq_grupo_defic = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete co_grupo_defic where sq_grupo_defic = p_chave;
   End If;
end SP_PutCOGRDEF;
/
