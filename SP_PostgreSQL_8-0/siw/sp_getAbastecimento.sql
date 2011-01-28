create or replace FUNCTION SP_GetAbastecimento
   (p_chave             numeric,
    p_chave_aux         numeric,
    p_cliente           numeric,    
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os grupos de veículos
   open p_result for 
         select a.sq_abastecimento chave, a.sq_veiculo, a.data, a.hodometro, a.litros, a.valor, a.local, 
                b.marca, b.modelo, b.placa, b.ano_modelo, b.ano_fabricacao, 
                substr(b.placa,1,3)||'-'||substr(b.placa,4)||' - '||b.marca||' '||b.modelo as nm_veiculo
           from sr_abastecimento   a
              inner join sr_veiculo b on (a.sq_veiculo = b.sq_veiculo)
      where b.cliente = p_cliente
        and ((p_chave is null)     or (p_chave      is not null and a.sq_abastecimento = p_chave))      
        and ((p_chave_aux is null) or (p_chave_aux  is not null and a.sq_veiculo       = p_chave_aux));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;