create or replace procedure sp_ajustaPesquisaMaterial(p_cliente in number, p_material in number default null, p_todos in varchar2 default null) is
  cursor c_dados is
    -- Recupera um ou todos os itens, indicando a pesquisa como VENCIDA se nenhuma está válida ou se não tiver pesquisa registrada
    select sq_material, valor_menor,  valor_maior, valor_medio, inicio, fim,
           case when fim is null 
                then 'VENCIDA'
                else case when fim >= trunc(sysdate) 
                          then 'ATIVA' 
                          else 'VENCIDA' 
                     end
           end as validade
      from (select a.sq_material, min(b.valor_unidade) as valor_menor, max(b.valor_unidade) as valor_maior, round(avg(b.valor_unidade),4) as valor_medio,
                   max(inicio) as inicio, max(fim) as fim
              from cl_material                   a
                   left  join cl_item_fornecedor b on (a.sq_material = b.sq_material and b.pesquisa = 'S')
             where a.cliente = coalesce(p_cliente,0)
               and (coalesce(p_todos,'-') = 'TODOS' or (coalesce(p_todos,'-') <> 'TODOS' and a.sq_material = coalesce(p_material,0)))
            group by a.sq_material
           );
begin
  for crec in c_dados loop
     If crec.validade = 'ATIVA' Then
        -- Ajusta os dados dos materiais que têm pesquisa ativa
        update cl_material a set
           a.pesquisa_data        = crec.inicio,
           a.pesquisa_validade    = crec.fim,
           a.pesquisa_preco_menor = crec.valor_menor,
           a.pesquisa_preco_maior = crec.valor_maior,
           a.pesquisa_preco_medio = crec.valor_medio
        where a.sq_material = crec.sq_material;
     Else
        -- Ajusta os dados dos materiais que têm pesquisa ativa
        update cl_material a set
           a.pesquisa_data        = crec.inicio,
           a.pesquisa_validade    = crec.fim,
           a.pesquisa_preco_menor = null,
           a.pesquisa_preco_maior = null,
           a.pesquisa_preco_medio = null
        where a.sq_material = crec.sq_material;
     End If;
  end loop;
end sp_ajustaPesquisaMaterial;
/
