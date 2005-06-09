create or replace procedure SP_GetSiwCliList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera os clienntes do SIW
   open p_result for
      select b.sq_pessoa, b.nome_resumido, b.nome, b.nome_indice,
             a.ativacao, a.bloqueio, a.desativacao, c.cnpj,
             d.sq_cidade, d.nome cidade, d.co_uf uf, d.sq_pais
      from siw_cliente        a,
           co_cidade          d,
           co_pessoa          b,
           co_pessoa_juridica c
      where (a.sq_cidade_padrao = d.sq_cidade (+))
        and (b.sq_pessoa = c.sq_pessoa (+))
        and a.sq_pessoa          = b.sq_pessoa
      order by b.nome_indice;
end SP_GetSiwCliList;
/

