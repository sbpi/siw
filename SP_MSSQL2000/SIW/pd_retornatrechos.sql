create function pd_retornatrechos(@p_chave int) return varchar as
-- Retorna os trechos de uma viagem, recebendo a chave de siw_solicitacao
  
  declare @w_texto   varchar;
  set @w_texto = '';

  declare  @w_chegada varchar;

  cursor c_deslocamentos is
    select a.sq_deslocamento,
           b.nome as nm_cidade_origem,
           d.nome as nm_cidade_destino,
           case c.padrao when 'S' then b.nome+'-'+b.co_uf else b.nome+'-'+c.nome end as nm_origem,
           case e.padrao when 'S' then d.nome+'-'+d.co_uf else d.nome+'-'+e.nome end as nm_destino
      from pd_deslocamento        a
           inner   join co_cidade b on (a.origem  = b.sq_cidade)
             inner join co_pais   c on (b.sq_pais = c.sq_pais)
           inner   join co_cidade d on (a.destino = d.sq_cidade)
             inner join co_pais   e on (d.sq_pais = e.sq_pais)
     where a.sq_siw_solicitacao = @p_chave
    order by a.saida, a.chegada;
begin
  -- Concatena em @w_texto cada cidade encontrada
  for crec in c_deslocamentos loop 
   set @w_chegada = crec.nm_cidade_destino; 
   set @w_texto   = @w_texto + crec.nm_cidade_origem +' - '; 
  end loop;
  -- Configura o retorno
  if len(@w_texto) > 0 begin set @w_texto = @w_texto+' '+@w_chegada; else set @w_texto = null; end;
  return @w_texto;
end;

