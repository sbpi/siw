create or replace function pd_retornatrechos(p_chave in number) return varchar2 is
-- Retorna os trechos de uma viagem, recebendo a chave de siw_solicitacao
  
  w_texto   varchar2(2000) := '';
  w_chegada varchar2(100);

  cursor c_deslocamentos is
    select a.sq_deslocamento,
           b.nome nm_cidade_origem,
           d.nome nm_cidade_destino,
           case c.padrao when 'S' then b.nome||'-'||b.co_uf else b.nome||'-'||c.nome end nm_origem,
           case e.padrao when 'S' then d.nome||'-'||d.co_uf else d.nome||'-'||e.nome end nm_destino
      from pd_deslocamento        a
           inner   join co_cidade b on (a.origem  = b.sq_cidade)
             inner join co_pais   c on (b.sq_pais = c.sq_pais)
           inner   join co_cidade d on (a.destino = d.sq_cidade)
             inner join co_pais   e on (d.sq_pais = e.sq_pais)
     where a.sq_siw_solicitacao = p_chave
    order by a.saida, a.chegada;
begin
  -- Concatena em w_texto cada cidade encontrada
  for crec in c_deslocamentos loop 
    w_chegada := crec.nm_cidade_destino; 
    w_texto   := w_texto || crec.nm_cidade_origem ||' - '; 
  end loop;
  -- Configura o retorno
  if length(w_texto) > 0 then w_texto := w_texto||' '||w_chegada; else w_texto := null; end if;
  return w_texto;
end pd_retornatrechos;
/
