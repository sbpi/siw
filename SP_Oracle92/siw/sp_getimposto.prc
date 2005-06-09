create or replace procedure SP_GetImposto
   (p_chave     in number default null,
    p_cliente   in number,
    p_result    out sys_refcursor) is
begin
      -- Recupera os tipos de contrato do cliente
      open p_result for 
         select a.sq_imposto chave, a.nome, a.descricao, a.sigla, a.dia_pagamento, a.esfera,  
                a.calculo, a.ativo,
                case a.calculo when 0 then 'Nominal' 
                               when 1 then 'Retenção' 
                               end  nm_calculo,
                case a.esfera  when 'F' then 'Federal' 
                               when 'E' then 'Estadual' 
                               when 'M' then 'Municipal' 
                               end  nm_esfera,                               
                case a.ativo   when 'S' then 'Sim' 
                               else 'Não' 
                               end nm_ativo
           from fn_imposto           a
     where a.cliente = p_cliente and
     ((p_chave              is null) or (p_chave              is not null and a.sq_imposto= p_chave));
end SP_GetImposto;
/

