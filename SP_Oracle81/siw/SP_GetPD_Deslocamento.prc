create or replace procedure SP_GetPD_Deslocamento
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as demandas que o usuário pode ver
   open p_result for
      select a.*,
             b.sq_cidade cidade_orig, b.co_uf uf_orig, b.sq_pais pais_orig,
             d.sq_cidade cidade_dest, d.co_uf uf_dest, d.sq_pais pais_dest,
             decode(c.padrao,'S',b.nome||'-'||b.co_uf,b.nome||' ('||c.nome||')') nm_origem,
             decode(e.padrao,'S',d.nome||'-'||d.co_uf,d.nome||' ('||e.nome||')') nm_destino
        from pd_deslocamento  a,
             co_cidade        b,
             co_pais          c,
             co_cidade        d,
             co_pais          e
       where a.origem             = b.sq_cidade
         and b.sq_pais            = c.sq_pais
         and a.destino            = d.sq_cidade
         and d.sq_pais            = e.sq_pais
         and a.sq_siw_solicitacao = p_chave
         and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_deslocamento = p_chave_aux));
End SP_GetPD_Deslocamento;
/
