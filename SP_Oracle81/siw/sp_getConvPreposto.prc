create or replace procedure SP_GetConvPreposto
   (p_chave       in number,
    p_chave_aux   in number default null,
    p_sq_pessoa   in number default null,
    p_result      out siw.sys_refcursor) is
begin
  -- Recupera os tipos de contrato do cliente
  open p_result for 
    select a.sq_acordo_outra_parte, a.sq_pessoa, b.nome nm_pessoa, b.nome_resumido, c.cpf,
           decode(sexo,'F','Feminino','Masculino') nm_sexo,
           c.rg_numero, c.rg_emissao, c.rg_emissor
      from ac_acordo_preposto    a,
           co_pessoa             b,     
           co_pessoa_fisica      c     
    where (a.sq_pessoa          = b.sq_pessoa)
      and (a.sq_pessoa          = c.sq_pessoa)
      and (a.sq_siw_solicitacao = p_chave)
      and (p_chave_aux    is null or (p_chave_aux   is not null and a.sq_acordo_outra_parte = p_chave_aux ))
      and (p_sq_pessoa    is null or (p_sq_pessoa   is not null and a.sq_pessoa             = p_sq_pessoa));      
end SP_GetConvPreposto;
/
