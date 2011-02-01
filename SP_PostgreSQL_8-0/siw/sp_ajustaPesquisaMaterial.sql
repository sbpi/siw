CREATE OR REPLACE FUNCTION sp_ajustaPesquisaMaterial(
     p_cliente numeric, 
     p_material numeric,
     p_todos varchar) RETURNS VOID AS $$
declare
     
  w_cont      numeric := 0;
  c_dados cursor (tipo varchar) for
    select a.sq_material, min(b.valor_unidade) as valor_menor, max(b.valor_unidade) as valor_maior, round(avg(b.valor_unidade),4) as valor_medio,
           max(inicio) as inicio, max(fim) as fim
      from cl_material                   a
           inner join cl_item_fornecedor b on (a.sq_material = b.sq_material)
     where a.cliente = coalesce(p_cliente,0)
       and (coalesce(p_todos,'-') = 'TODOS' or (coalesce(p_todos,'-') <> 'TODOS' and a.sq_material = coalesce(p_material,0)))
       and (tipo = 'NORMAL' and b.fim >= now() or (tipo <> 'NORMAL'))
       and b.pesquisa = 'S'
    group by a.sq_material;
begin
  -- Ajusta os dados dos materiais que têm pesquisa válida
  for crec in c_dados('NORMAL') loop
     w_cont := 1;
     update cl_material a set
        a.pesquisa_data        = crec.inicio,
        a.pesquisa_validade    = crec.fim,
        a.pesquisa_preco_menor = crec.valor_menor,
        a.pesquisa_preco_maior = crec.valor_maior,
        a.pesquisa_preco_medio = crec.valor_medio
     where a.sq_material = crec.sq_material;
  end loop;
  If w_cont = 0 Then
     for crec in c_dados('INVALIDA') loop
        update cl_material a set
           a.pesquisa_data        = crec.inicio,
           a.pesquisa_validade    = crec.fim,
           a.pesquisa_preco_menor = crec.valor_menor,
           a.pesquisa_preco_maior = crec.valor_maior,
           a.pesquisa_preco_medio = crec.valor_medio
        where a.sq_material = crec.sq_material;  
     end loop;
  End If;
END $$ LANGUAGE 'plpgsql' VOLATILE;
