create or replace function SP_GetFoneData
   (p_chave      numeric,
    p_result     refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do endereco informado
   open p_result for 
      select b.*, c.sq_pais, c.co_uf 
      from co_pessoa_telefone b, co_cidade c  
      where b.sq_cidade          = c.sq_cidade 
        and b.sq_pessoa_telefone = p_chave;
   return p_result;
end $$ language 'plpgsql' volatile;

