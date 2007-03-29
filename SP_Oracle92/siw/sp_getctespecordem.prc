create or replace procedure SP_GetEspecOrdem
   (p_chave   in  number,
    p_result  out sys_refcursor
   ) is
begin
   -- Recupera as etapas acima da informada
   open p_result for 
      select sq_especificacao_despesa, especificacao_pai, nome, codigo
        from ct_especificacao_despesa
      start with sq_especificacao_despesa = p_chave
      connect by prior especificacao_pai = sq_especificacao_despesa; 
end SP_GetEspecOrdem;
/
