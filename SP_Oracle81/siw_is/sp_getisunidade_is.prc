create or replace procedure SP_GetIsUnidade_IS
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out siw.siw.sys_refcursor) is
begin
   -- Recupera as unidades de modulo infra-sig
   open p_result for 
      select a.sq_unidade chave, a.administrativa, a.planejamento , b.nome, b.sigla
        from is_unidade                             a,
             siw.eo_unidade         b, 
             siw.co_pessoa_endereco c 
       where (a.sq_unidade = b.sq_unidade (+))
         and (b.sq_pessoa_endereco = c.sq_pessoa_endereco (+))
         and c.sq_pessoa = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
end SP_GetIsUnidade_IS;
/

