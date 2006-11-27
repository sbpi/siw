create or replace procedure SP_GetOpiniao
   (p_chave     in number default null,
    p_cliente   in number,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os tipos de opinião do cliente
      open p_result for 
         select a.sq_siw_opiniao chave, a.nome, a.ordem
           from siw_opiniao   a
      where a.cliente     = p_cliente
        and ((p_chave     is null) or (p_chave     is not null and a.sq_siw_opiniao = p_chave));
   End If;
end SP_GetOpiniao;
/
