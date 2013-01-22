create or replace procedure SP_GetValorDiaria
   (p_cliente           in  number,
    p_chave             in  number default null,
    p_result            out sys_refcursor
   ) is
   
begin
   -- Recupera os Valores de Diária
   open p_result for
      select a.sq_valor_diaria as chave, a.continente, a.nacional, a.sq_pais, a.sq_cidade,
             a.sq_categoria_diaria, a.valor, a.tipo_diaria, a.sq_moeda, 
             case a.nacional when 'S' then 'Sim' else 'Não' end as nm_nacional,
             case a.continente
                  when 1 then 'América'
                  when 2 then 'Europa'
                  when 3 then 'Ásia'
                  when 4 then 'África'
                  when 5 then 'Oceania' 
             end as nm_continente,
             case tipo_diaria
                  when 'D' then 'Diária'
                  when 'H' then 'Hospedagem'
                  when 'V' then 'Locação'
             end as nm_tipo_diaria,
             b.nome as nm_pais,
             c.nome as nm_cidade,
             c.co_uf as nm_uf,
             d.nome as nm_moeda, d.sigla as sg_moeda,
             e.nome as nm_categoria_diaria, e.sigla as sg_categoria_diaria
       from pd_valor_diaria                 a
            left   join co_pais             b  on (a.sq_pais             = b.sq_pais)
            left   join co_cidade           c  on (a.sq_cidade           = c.sq_cidade)
            inner  join co_moeda            d  on (a.sq_moeda            = d.sq_moeda)
            inner  join pd_categoria_diaria e  on (a.sq_categoria_diaria = e.sq_categoria_diaria)
      where a.cliente = p_cliente
        and ((p_chave is null)  or (p_chave is not null and a.sq_valor_diaria = p_chave));
end SP_GetValorDiaria;
/
