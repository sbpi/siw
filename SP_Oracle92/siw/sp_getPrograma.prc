create or replace procedure SP_GetMeioTransporte
   (p_chave             in number default null,
    p_cliente           in number,    
    p_result    out sys_refcursor) is
begin
   -- Recupera os grupos de veículos
   open p_result for 
         select a.sq_meio_transporte, a.nome, a.aereo, a.rodoviario, a.ferroviario, a.aquaviario, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' as nm_ativo
           from pd_meio_transporte a
      where b.cliente = p_cliente
        and b.sq_plano_pai > 0 
        and ((p_chave is null)  or (p_chave is not null and b.sq_plano = p_chave));
end SP_GetMeioTransporte;
/
