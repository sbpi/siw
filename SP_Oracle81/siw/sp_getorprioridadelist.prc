create or replace procedure SP_GetOrPrioridadeList
   (p_chave           in  number default null,
    p_cliente         in  number,
    p_sq_orprioridade in  number default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as iniciativas prioritárias do Governo
   open p_result for
      select a.sq_orprioridade chave, a.nome, b.sq_orprioridade existe, a.codigo, a.ordem,
             decode(a.ativo,'S','Sim','Não') nm_ativo,
             decode(a.padrao,'S','Sim','Não') nm_padrao
        from or_prioridade a,
             or_acao_prioridade b
       where a.sq_orprioridade    = b.sq_orprioridade (+)
         and b.sq_siw_solicitacao = p_chave
         and a.cliente = p_cliente
         and ((p_sq_orprioridade is null) or (p_sq_orprioridade is not null and a.sq_orprioridade <> p_sq_orprioridade));
end SP_GetOrPrioridadeList;
/

