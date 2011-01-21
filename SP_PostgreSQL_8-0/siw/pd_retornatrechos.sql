create or replace function pd_retornatrechos(p_chave in numeric) returns varchar as $$
-- Retorna os trechos de uma viagem, recebendo a chave de siw_solicitacao
declare
  w_texto   varchar(2000) := '';
  w_chegada varchar(100);

  c_sq_deslocamento    pd_deslocamento.sq_deslocamento%type;
  c_nm_cidade_origem   co_cidade.nome%type;
  c_nm_cidade_destino  co_cidade.nome%type;
  c_nm_origem          varchar(512);
  c_nm_destino         varchar(512);

  c_deslocamentos cursor (l_chave numeric) for
    select a.sq_deslocamento,
           b.nome as nm_cidade_origem,
           d.nome as nm_cidade_destino,
           case c.padrao when 'S' then b.nome||'-'||b.co_uf else b.nome||'-'||c.nome end as nm_origem,
           case e.padrao when 'S' then d.nome||'-'||d.co_uf else d.nome||'-'||e.nome end as nm_destino
      from pd_deslocamento        a
           inner   join co_cidade b on (a.origem  = b.sq_cidade)
             inner join co_pais   c on (b.sq_pais = c.sq_pais)
           inner   join co_cidade d on (a.destino = d.sq_cidade)
             inner join co_pais   e on (d.sq_pais = e.sq_pais)
     where a.sq_siw_solicitacao = l_chave
    order by a.saida, a.chegada;
begin
  -- Concatena em w_texto cada cidade encontrada
  open c_deslocamentos (p_chave);
  loop
    fetch c_deslocamentos into c_sq_deslocamento, c_nm_cidade_origem, c_nm_cidade_destino, c_nm_origem, c_nm_destino;
    If Not Found Then Exit; End If;
    w_chegada := c_nm_cidade_destino; 
    w_texto   := w_texto || c_nm_cidade_origem ||' - '; 
  end loop;
  close c_deslocamentos;
  
  -- Configura o retorno
  if length(w_texto) > 0 then w_texto := w_texto||' '||w_chegada; else w_texto := null; end if;
  return w_texto;
end; $$ language 'plpgsql' volatile;
