CREATE OR REPLACE FUNCTION siw.SP_GetImposto
   (p_chave     numeric,
    p_cliente   numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
      -- Recupera os tipos de contrato do cliente
      open p_result for
         select a.sq_imposto as chave, a.nome, a.descricao, a.sigla, a.dia_pagamento, a.esfera,
                a.calculo, a.ativo,
                case a.calculo when 0 then 'Nominal'
                               when 1 then 'Retenção'
                               end  as nm_calculo,
                case a.esfera  when 'F' then 'Federal'
                               when 'E' then 'Estadual'
                               when 'M' then 'Municipal'
                               end as nm_esfera,
                case a.ativo   when 'S' then 'Sim'
                               else 'Não'
                               end as nm_ativo
           from siw.fn_imposto           a
     where a.cliente = p_cliente and
     ((p_chave              is null) or (p_chave              is not null and a.sq_imposto= p_chave));
     return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEtniaList
   (p_nome      varchar,
    p_ativo     varchar) OWNER TO siw;

