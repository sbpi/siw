create or replace procedure sp_ajustaPesquisaMaterial(p_cliente in number, p_material in number default null, p_todos in varchar2 default null) is
  cursor c_dados is
    -- Recupera um ou todos os itens, indicando a pesquisa como VENCIDA se nenhuma está válida ou se não tiver pesquisa registrada
    select a.sq_material, 
           case when b.sq_material is null then null     else b.valor_menor end as valor_menor, 
           case when b.sq_material is null then null     else b.valor_maior end as valor_maior, 
           case when b.sq_material is null then null     else b.valor_medio end as valor_medio,
           case when b.sq_material is null then c.inicio else b.inicio      end as inicio, 
           case when b.sq_material is null then c.fim    else b.fim         end as fim
      from cl_material                   a
           left  join (select sq_material, min(valor_unidade) as valor_menor, max(valor_unidade) as valor_maior, round(avg(valor_unidade),4) as valor_medio, max(inicio) as inicio, min(fim) as fim
                         from cl_item_fornecedor 
                        where pesquisa = 'S'
                          and fim      >= trunc(sysdate)
                       group by sq_material
                      )                  b on (a.sq_material = b.sq_material)
           left  join (select sq_material, min(valor_unidade) as valor_menor, max(valor_unidade) as valor_maior, round(avg(valor_unidade),4) as valor_medio, max(inicio) as inicio, min(fim) as fim
                         from cl_item_fornecedor 
                        where pesquisa = 'S'
                       group by sq_material
                      )                  c on (a.sq_material = c.sq_material)
     where a.cliente = coalesce(p_cliente,0)
       and (coalesce(p_todos,'-') = 'TODOS' or (coalesce(p_todos,'-') <> 'TODOS' and a.sq_material = coalesce(p_material,0)));
begin
  for crec in c_dados loop
     -- Ajusta os dados dos materiais que têm pesquisa ativa
     update cl_material a set
        a.pesquisa_data        = crec.inicio,
        a.pesquisa_validade    = crec.fim,
        a.pesquisa_preco_menor = crec.valor_menor,
        a.pesquisa_preco_maior = crec.valor_maior,
        a.pesquisa_preco_medio = crec.valor_medio
     where a.sq_material = crec.sq_material;
  end loop;
end sp_ajustaPesquisaMaterial;
/
