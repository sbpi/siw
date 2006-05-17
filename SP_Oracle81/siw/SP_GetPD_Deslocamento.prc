create or replace procedure SP_GetPD_Deslocamento
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao = 'DADFIN' Then
      open p_result for
         select a.*,
                b.sq_cidade cidade_orig, b.co_uf uf_orig, b.sq_pais pais_orig,
                d.sq_cidade cidade_dest, d.co_uf uf_dest, d.sq_pais pais_dest,
                decode(c.padrao,'S',b.nome||'-'||b.co_uf,b.nome||' ('||c.nome||')') nm_origem,
                decode(e.padrao,'S',d.nome||'-'||d.co_uf,d.nome||' ('||e.nome||')') nm_destino,
                f.sq_diaria, f.quantidade, f.valor
           from pd_deslocamento  a,
                co_cidade        b,
                co_pais          c,
                co_cidade        d,
                co_pais          e,
                pd_diaria        f
          where a.origem             = b.sq_cidade
            and b.sq_pais            = c.sq_pais
            and a.destino            = d.sq_cidade
            and d.sq_pais            = e.sq_pais
            and a.sq_siw_solicitacao = f.sq_siw_solicitacao (+)
            and a.destino            = f.sq_cidade (+)
            and a.sq_siw_solicitacao = p_chave;            
   Elsif p_restricao = 'DF' Then
      open p_result for
         select count(*) existe
           from pd_diaria
          where sq_siw_solicitacao = p_chave;
   Else
   --If p_restricao is null or p_restricao = 'PDINICIAL' or p_restricao = 'PDGERAL' Then
      -- Recupera as demandas que o usuário pode ver   
      open p_result for
         select a.*,
                b.sq_cidade cidade_orig, b.co_uf uf_orig, b.sq_pais pais_orig,
                d.sq_cidade cidade_dest, d.co_uf uf_dest, d.sq_pais pais_dest,
                decode(c.padrao,'S',b.nome||'-'||b.co_uf,b.nome||' ('||c.nome||')') nm_origem,
                decode(e.padrao,'S',d.nome||'-'||d.co_uf,d.nome||' ('||e.nome||')') nm_destino,
                f.nome nm_cia_transporte
           from pd_deslocamento   a,
                co_cidade         b,
                co_pais           c,
                co_cidade         d,
                co_pais           e,
                pd_cia_transporte f
          where a.origem             = b.sq_cidade
            and b.sq_pais            = c.sq_pais
            and a.destino            = d.sq_cidade
            and d.sq_pais            = e.sq_pais
            and a.sq_cia_transporte  = f.sq_cia_transporte (+)
            and a.sq_siw_solicitacao = p_chave
            and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_deslocamento = p_chave_aux));
   End If;
End SP_GetPD_Deslocamento;
/
