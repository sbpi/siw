create or replace procedure SP_GetImposto
   (p_chave     in number default null,
    p_cliente   in number,
    p_result    out siw.sys_refcursor) is
begin
      -- Recupera os tipos de contrato do cliente
      open p_result for
         select a.sq_imposto chave, a.nome, a.descricao, a.sigla, a.dia_pagamento, a.esfera,
                a.calculo, a.ativo,
                decode(a.calculo,0,'Nominal',1,'Retenção') nm_calculo,
                decode(a.esfera,'F','Federal','E','Estadual','M','Municipal') nm_esfera,
                decode(a.ativo,'S','Sim','Não') nm_ativo
           from fn_imposto           a
     where a.cliente = p_cliente and
     ((p_chave              is null) or (p_chave              is not null and a.sq_imposto= p_chave));
end SP_GetImposto;
/

