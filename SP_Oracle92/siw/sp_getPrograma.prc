create or replace procedure SP_GetPrograma
   (p_chave             in number default null,
    p_cliente           in number,    
    p_result    out sys_refcursor) is
begin
   -- Recupera os grupos de veículos
   open p_result for 
         select a.sq_siw_solicitacao, a.codigo_interno, a.titulo nm_programa,
                b.sq_plano, b.cliente, b.titulo nm_plano
           from pe_programa          a
              inner join pe_plano    b on (a.sq_pehorizonte = b.sq_plano)
      where b.cliente = p_cliente
        and ((p_chave is null)  or (p_chave is not null and b.sq_plano = p_chave));
end SP_GetPrograma;
/
