create or replace procedure SP_GetPD_Deslocamento
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   If p_restricao = 'DADFIN' Then
      open p_result for
         select a.sq_deslocamento, a.sq_siw_solicitacao, a.origem, a.destino, a.sq_cia_transporte, 
                a.saida, a.chegada, a.codigo_cia_transporte, a.valor_trecho, a.codigo_voo,
                to_char(a.saida,'dd/mm/yyyy, hh24:mi:ss') phpdt_saida,
                to_char(a.chegada,'dd/mm/yyyy, hh24:mi:ss') phpdt_chegada,
                b.sq_cidade cidade_orig, b.co_uf uf_orig, b.sq_pais pais_orig,
                d.sq_cidade cidade_dest, d.co_uf uf_dest, d.sq_pais pais_dest,
                case c.padrao 
                    when 'S' 
                    then b.nome||'-'||b.co_uf 
                    else b.nome||' ('||c.nome||')'
                    end nm_origem,
                case e.padrao 
                    when 'S'
                    then d.nome||'-'||d.co_uf
                    else d.nome||' ('||e.nome||')'
                    end nm_destino,
                f.sq_diaria, f.quantidade, f.valor
           from pd_deslocamento          a
                  inner      join   co_cidade b on (a.origem             = b.sq_cidade)
                    inner    join   co_pais   c on (b.sq_pais            = c.sq_pais)
                  inner      join   co_cidade d on (a.destino            = d.sq_cidade)
                    inner    join   co_pais   e on (d.sq_pais            = e.sq_pais)
                  left outer join   pd_diaria f on (a.sq_siw_solicitacao = f.sq_siw_solicitacao and
                                                    a.destino            = f.sq_cidade)
          where a.sq_siw_solicitacao = p_chave;            
   Elsif p_restricao = 'DF' Then
      open p_result for
         select count(*) existe
           from pd_diaria
          where sq_siw_solicitacao = p_chave;
   Else
   --If p_restricao is null or p_restricao = 'PDINICIAL' or p_restricao = 'PDGERAL' Then
      -- Recupera as demandas que o usuário pode ver   
      open p_result for
         select a.sq_deslocamento, a.sq_siw_solicitacao, a.origem, a.destino, a.sq_cia_transporte, 
                a.saida, a.chegada, a.codigo_cia_transporte, a.valor_trecho, a.codigo_voo, a.passagem, 
                case a.passagem when 'S' then 'Sim' else 'Não' end as nm_passagem,
                to_char(a.saida,'dd/mm/yyyy, hh24:mi:ss') as phpdt_saida,
                to_char(a.chegada,'dd/mm/yyyy, hh24:mi:ss') as phpdt_chegada,
                b.sq_cidade cidade_orig, b.co_uf uf_orig, b.sq_pais as pais_orig,
                d.sq_cidade cidade_dest, d.co_uf uf_dest, d.sq_pais as pais_dest,
                case c.padrao when 'S' then b.nome||'-'||b.co_uf else b.nome||' ('||c.nome||')' end as nm_origem,
                case e.padrao when 'S' then d.nome||'-'||d.co_uf else d.nome||' ('||e.nome||')' end as nm_destino,
                f.nome as nm_cia_transporte,
                g.sq_meio_transporte, g.nome as nm_meio_transporte
           from pd_deslocamento                 a
                inner   join co_cidade          b on (a.origem             = b.sq_cidade)
                  inner join co_pais            c on (b.sq_pais            = c.sq_pais)
                inner   join co_cidade          d on (a.destino            = d.sq_cidade)
                  inner join co_pais            e on (d.sq_pais            = e.sq_pais)
               left     join pd_cia_transporte  f on (a.sq_cia_transporte  = f.sq_cia_transporte)
               left     join pd_meio_transporte g on (a.sq_meio_transporte = g.sq_meio_transporte)
          where a.sq_siw_solicitacao = p_chave
            and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_deslocamento = p_chave_aux));   
   End If;         
End SP_GetPD_Deslocamento;
/
