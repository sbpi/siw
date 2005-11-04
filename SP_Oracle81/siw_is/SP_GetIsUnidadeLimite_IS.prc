create or replace procedure SP_GetIsUnidadeLimite_IS
   (p_chave     in  number default null,
    p_ano       in  number default null,
    p_cliente   in  number,
    p_result    out siw.siw.sys_refcursor) is
begin
   -- Recupera as unidades de modulo infra-sig
   open p_result for 
      select a.sq_unidade chave, a.administrativa, a.planejamento , b.nome, b.sigla,
             d.ano,              d.limite_orcamento
        from is_unidade                             a,
             siw.eo_unidade         b, 
             siw.co_pessoa_endereco c,
             is_unidade_limite      d 
       where (a.sq_unidade = b.sq_unidade)
         and (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
         and (b.sq_unidade         = d.sq_unidade)
         and c.sq_pessoa = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave))
         and ((p_ano   is null) or (p_ano   is not null and d.ano        = p_ano));
end SP_GetIsUnidadeLimite_IS;
/
