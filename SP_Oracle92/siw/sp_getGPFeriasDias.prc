create or replace procedure sp_GetGPFeriasDias
  (p_chave          in number,
   p_cliente        in number,
   p_ativo          in varchar2,
   p_result         out sys_refcursor) is
begin
  open p_result for 
  select sq_ferias_dias as chave, 
         cliente,
         faixa_inicio,
         faixa_fim,
         dias_ferias,
         ativo
    from gp_ferias_dias
   where cliente = p_cliente 
     and((p_chave is null) or p_chave is not null and sq_ferias_dias = p_chave)
     and ((p_ativo is null) or p_ativo is not null and  ativo = p_ativo)
   order by faixa_inicio;
end SP_GetGPFeriasDias;
/
