create or replace function pd_retornatrechos(p_chave in number) return varchar2 is
-- Retorna os trechos de uma viagem, recebendo a chave de siw_solicitacao
  
  w_texto   varchar2(2000) := '';
  w_cont    number(10) := 0;
  w_reg     number(10) := 0;
  i         number(10);

  cursor c_deslocamentos is
    select 1 as tipo, b.nome as nm_cidade, a.saida, a.chegada
      from pd_deslocamento              a
           inner   join co_cidade       b on (a.destino                 = b.sq_cidade)
             inner join co_pais         c on (b.sq_pais                 = c.sq_pais)
           left    join pd_diaria       d on (a.sq_deslocamento         = d.sq_deslocamento_chegada and
                                              (d.diaria                 = 'S' or 
                                               d.hospedagem             = 'S' or 
                                               d.veiculo                = 'S'
                                              )
                                             )
     where a.sq_siw_solicitacao = p_chave
       and a.tipo               = 'S'
       and d.sq_diaria          is null
    UNION
    select 2 as tipo, b.nome as nm_cidade, d.saida, d.chegada
      from pd_diaria                    a
           inner   join co_cidade       b on (a.sq_cidade               = b.sq_cidade)
             inner join co_pais         c on (b.sq_pais                 = c.sq_pais)
           inner   join pd_deslocamento d on (a.sq_deslocamento_chegada = d.sq_deslocamento)
     where a.sq_siw_solicitacao = p_chave
       and a.tipo               = 'S'
       and (a.diaria            = 'S' or a.hospedagem = 'S' or a.veiculo = 'S')
    order by saida, chegada;
begin
  -- Verifica a quantidade de registros
  for crec in c_deslocamentos loop w_reg := w_reg + 1; end loop;
  
  -- Concatena em w_texto cada cidade encontrada
  i := 0;
  for crec in c_deslocamentos loop 
    w_texto   := w_texto || case w_cont when 0 then '' else ' - ' end || crec.nm_cidade; 
    w_cont    := w_cont + 1;
    i         := i + 1;
    If i >= (w_reg-1) and crec.tipo = 1 Then exit; End If;
  end loop;
  -- Configura o retorno
  if length(w_texto) = 0 then w_texto := null; end if;
  return w_texto;
end pd_retornatrechos;
/
