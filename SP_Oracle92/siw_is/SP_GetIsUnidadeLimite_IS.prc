create or replace procedure SP_GetIsUnidadeLimite_IS
   (p_chave     in  number default null,
    p_ano       in  number default null,
    p_cliente   in  number,
    p_result    out sys_refcursor) is
begin
   -- Recupera as unidades de modulo infra-sig
   open p_result for 
      select a.sq_unidade chave, a.administrativa, a.planejamento , b.nome, b.sigla,
             d.ano,              d.limite_orcamento
        from is_unidade                            a
               inner   join siw.eo_unidade         b on (a.sq_unidade         = b.sq_unidade)
                 inner join siw.co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
                 inner join is_unidade_limite      d on (b.sq_unidade         = d.sq_unidade)
       where c.sq_pessoa = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave))
         and ((p_ano   is null) or (p_ano   is not null and d.ano        = p_ano));
end SP_GetIsUnidadeLimite_IS;
/
