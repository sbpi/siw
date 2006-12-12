create or replace procedure SP_GetAbastecimento
   (p_chave             in number default null,
    p_chave_aux         in number default null,
    p_cliente           in number default null,    
    p_result    out sys_refcursor) is
begin
   -- Recupera os grupos de veículos
   open p_result for 
         select a.sq_abastecimento chave, a.sq_veiculo, a.data, a.hodometro, a.litros, a.valor, a.local, b.modelo, b.placa
           from sr_abastecimento   a
              inner join sr_veiculo b on (a.sq_veiculo = b.sq_veiculo)
      where b.cliente = p_cliente
        and ((p_chave is null)     or (p_chave      is not null and a.sq_abastecimento = p_chave))      
        and ((p_chave_aux is null) or (p_chave_aux  is not null and a.sq_veiculo       = p_chave_aux));
end SP_GetAbastecimento;
/
