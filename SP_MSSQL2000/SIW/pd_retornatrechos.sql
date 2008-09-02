alter function dbo.pd_retornatrechos(@p_chave int) returns varchar as
begin
  -- Retorna os trechos de uma viagem, recebendo a chave de si@w_solicitacao
  
  Declare @w_texto      varchar(2000);
  Declare @w_chegada    varchar(100);
  Declare @w_desloc     numeric(18);
  Declare @w_origem     varchar(100);
  Declare @w_destino    varchar(100);
  Declare @w_nm_origem  varchar(100);
  Declare @w_nm_destino varchar(100);
  Set @w_texto = '';

  Declare c_deslocamentos cursor for
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

  -- Concatena em @w_texto cada cidade encontrada
  Open c_deslocamentos
  Fetch Next from c_deslocamentos into @w_desloc, @w_origem, @w_destino, @w_nm_origem, @w_nm_destino
  While @@Fetch_Status = 0 Begin
     Set @w_chegada = @w_destino; 
     Set @w_texto   = @w_texto + @w_origem +' - '; 
     Fetch Next from c_deslocamentos into @w_desloc, @w_origem, @w_destino, @w_nm_origem, @w_nm_destino
  End
  Close c_deslocamentos
  Deallocate c_deslocamentos
  
  -- Configura o retorno
  if len(@w_texto) > 0 Set @w_texto = @w_texto+' '+@w_chegada; else Set @w_texto = null;
  return @w_texto;
end
