create or replace procedure sp_ajustaPesquisaMaterial(p_cliente in number, p_material in number, p_todos in varchar2 default null) is
  w_inicio    date := null;
  w_fim       date := null;

  cursor c_dados is
    select a.sq_material, min(b.valor_unidade) as valor_menor, max(b.valor_unidade) as valor_maior, round(avg(b.valor_unidade),4) as valor_medio
      from cl_material                   a
           inner join cl_item_fornecedor b on (a.sq_material = b.sq_material)
     where a.cliente = coalesce(p_cliente,0)
       and (coalesce(p_todos,'-') = 'TODOS' or (coalesce(p_todos,'-') <> 'TODOS' and a.sq_material = coalesce(p_material,0)))
--       and b.fim >= sysdate
    group by a.sq_material;
begin
  -- Reinicializa a pesquisa de todos os materiais do cliente
  update cl_material a set
     a.pesquisa_data        = null,
     a.pesquisa_validade    = null,
     a.pesquisa_preco_menor = null,
     a.pesquisa_preco_maior = null,
     a.pesquisa_preco_medio = null
  where a.cliente     = coalesce(p_cliente,0)
    and (coalesce(p_todos,'-') = 'TODOS' or (coalesce(p_todos,'-') <> 'TODOS' and a.sq_material = coalesce(p_material,0)));
    
  -- Ajusta os dados dos materiais que têm pesquisa válida
  for crec in c_dados loop
     select max(inicio), max(fim) into w_inicio, w_fim
       from cl_item_fornecedor
      where sq_material = crec.sq_material;
      
     update cl_material a set
        a.pesquisa_data        = w_inicio,
        a.pesquisa_validade    = w_fim,
        a.pesquisa_preco_menor = crec.valor_menor,
        a.pesquisa_preco_maior = crec.valor_maior,
        a.pesquisa_preco_medio = crec.valor_medio
     where a.sq_material = crec.sq_material;
  end loop;
end sp_ajustaPesquisaMaterial;
/
