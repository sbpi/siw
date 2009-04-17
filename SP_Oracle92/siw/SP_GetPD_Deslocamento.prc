create or replace procedure SP_GetPD_Deslocamento
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_tipo      in varchar2,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   If p_restricao = 'DADFIN' Then
      open p_result for
         select a.sq_deslocamento, a.sq_siw_solicitacao, a.origem, a.destino, a.sq_cia_transporte, 
                a.saida, a.chegada, a.codigo_cia_transporte, a.valor_trecho, a.codigo_voo, a.sq_bilhete,
                a.aeroporto_origem, a.aeroporto_destino,
                trunc(a.chegada)-trunc(a.saida) as dias_deslocamento,
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
                f.sq_diaria, f.quantidade, f.valor, f.hospedagem_checkin, f.hospedagem_checkout, f.hospedagem_observacao,
                f.veiculo_retirada, f.veiculo_devolucao
           from pd_deslocamento          a
                  inner      join   co_cidade b on (a.origem             = b.sq_cidade)
                    inner    join   co_pais   c on (b.sq_pais            = c.sq_pais)
                  inner      join   co_cidade d on (a.destino            = d.sq_cidade)
                    inner    join   co_pais   e on (d.sq_pais            = e.sq_pais)
                  left outer join   pd_diaria f on (a.sq_siw_solicitacao = f.sq_siw_solicitacao and
                                                    a.destino            = f.sq_cidade and
                                                    a.sq_deslocamento    = f.sq_deslocamento_saida and
                                                    f.tipo               = p_tipo
                                                   )
          where a.sq_siw_solicitacao = p_chave
            and a.tipo               = p_tipo;
   Elsif p_restricao = 'PDDIARIA' Then
      open p_result for
         select a.sq_deslocamento, a.sq_siw_solicitacao, a.origem, a.destino, a.sq_cia_transporte, 
                a.saida, a.chegada, a.codigo_cia_transporte, a.valor_trecho, a.codigo_voo,
                a.compromisso, a.sq_bilhete,
                a.aeroporto_origem, a.aeroporto_destino,
                trunc(a.chegada)-trunc(a.saida) as dias_deslocamento,
                to_char(a.saida,'dd/mm/yyyy, hh24:mi:ss') phpdt_saida,
                to_char(a.chegada,'dd/mm/yyyy, hh24:mi:ss') phpdt_chegada,
                case a.compromisso when 'S' then 'Sim' else 'Não' end as nm_compromisso,
                b.sq_cidade cidade_orig, b.co_uf uf_orig, b.sq_pais pais_orig,
                d.sq_cidade cidade_dest, d.co_uf uf_dest, d.sq_pais pais_dest,
                case c.padrao 
                    when 'S' 
                    then b.nome||'-'||b.co_uf 
                    else b.nome||' ('||c.nome||')'
                end as nm_origem,
                case e.padrao 
                    when 'S'
                    then d.nome||'-'||d.co_uf
                    else d.nome||' ('||e.nome||')'
                end as nm_destino,
                c.padrao as origem_nacional,
                e.padrao as destino_nacional,
                f.sq_diaria, f.justificativa_diaria, f.justificativa_veiculo,
                f.diaria, f.quantidade, f.valor, g1.sigla as sg_moeda_diaria, g.valor as vl_diaria, g.sq_valor_diaria as sq_valor_diaria,
                f.hospedagem, f.hospedagem_qtd, f.hospedagem_valor, h1.sigla as sg_moeda_hospedagem, h.valor as vl_diaria_hospedagem, h.sq_valor_diaria as sq_diaria_hospedagem,
                f.veiculo, f.veiculo_qtd, f.veiculo_valor, i1.sigla as sg_moeda_veiculo, i.valor as vl_diaria_veiculo, i.sq_valor_diaria as sq_diaria_veiculo,
                f.hospedagem_checkin, f.hospedagem_checkout, f.hospedagem_observacao, f.veiculo_retirada, f.veiculo_devolucao,
                m.sq_pdvinculo_financeiro as sq_fin_dia,
                m1.sq_projeto_rubrica as sq_rub_dia, m1.codigo as cd_rub_dia, m1.nome as nm_rub_dia, m1.descricao as ds_rub_dia,
                m2.sq_tipo_lancamento as sq_lan_dia, m2.nome as nm_lan_dia,
                n.sq_pdvinculo_financeiro as sq_fin_hsp,
                n1.sq_projeto_rubrica as sq_rub_hsp, n1.codigo as cd_rub_hsp, n1.nome as nm_rub_hsp, n1.descricao as ds_rub_hsp,
                n2.sq_tipo_lancamento as sq_lan_hsp, n2.nome as nm_lan_hsp,
                o.sq_pdvinculo_financeiro as sq_fin_vei,
                o1.sq_projeto_rubrica as sq_rub_vei, o1.codigo as cd_rub_vei, o1.nome as nm_rub_vei, o1.descricao as ds_rub_vei,
                o2.sq_tipo_lancamento as sq_lan_vei, o2.nome as nm_lan_vei,
                (select count(*) 
                   from pd_deslocamento            x
                        inner   join co_cidade     k on (x.destino = k.sq_cidade)
                          inner join co_pais       l on (k.sq_pais = l.sq_pais)
                  where x.sq_siw_solicitacao = a.sq_siw_solicitacao
                    and x.sq_deslocamento    <> a.sq_deslocamento 
                    and trunc(x.saida)       = trunc(a.chegada) 
                    and l.padrao             = 'N'
                ) saida_internacional,
                (select count(*) 
                   from pd_deslocamento            x
                        inner   join co_cidade     k on (x.origem  = k.sq_cidade)
                          inner join co_pais       l on (k.sq_pais = l.sq_pais)
                  where x.sq_siw_solicitacao = a.sq_siw_solicitacao
                    and x.sq_deslocamento    <> a.sq_deslocamento 
                    and trunc(x.chegada)       = trunc(a.saida) 
                    and l.padrao             = 'N'
                ) chegada_internacional
           from pd_deslocamento                       a
                inner      join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                inner      join co_cidade             b  on (a.origem                     = b.sq_cidade)
                  inner    join co_pais               c  on (b.sq_pais                    = c.sq_pais)
                inner      join co_cidade             d  on (a.destino                    = d.sq_cidade)
                  inner    join co_pais               e  on (d.sq_pais                    = e.sq_pais)
                left       join pd_diaria             f  on (a.sq_siw_solicitacao         = f.sq_siw_solicitacao and
                                                             a.destino                    = f.sq_cidade and
                                                             a.sq_deslocamento            = f.sq_deslocamento_saida and
                                                             f.tipo                       = p_tipo
                                                            )
                  left     join pd_vinculo_financeiro m  on (f.sq_pdvinculo_diaria        = m.sq_pdvinculo_financeiro)
                  left     join pj_rubrica            m1 on (m.sq_projeto_rubrica         = m1.sq_projeto_rubrica)
                  left     join fn_tipo_lancamento    m2 on (m.sq_tipo_lancamento         = m2.sq_tipo_lancamento)
                  left     join pd_vinculo_financeiro n  on (f.sq_pdvinculo_hospedagem    = n.sq_pdvinculo_financeiro)
                  left     join pj_rubrica            n1 on (n.sq_projeto_rubrica         = n1.sq_projeto_rubrica)
                  left     join fn_tipo_lancamento    n2 on (n.sq_tipo_lancamento         = n2.sq_tipo_lancamento)
                  left     join pd_vinculo_financeiro o  on (f.sq_pdvinculo_veiculo       = o.sq_pdvinculo_financeiro)
                  left     join pj_rubrica            o1 on (o.sq_projeto_rubrica         = o1.sq_projeto_rubrica)
                  left     join fn_tipo_lancamento    o2 on (o.sq_tipo_lancamento         = o2.sq_tipo_lancamento)
                inner      join pd_valor_diaria       g  on (g.sq_valor_diaria            = recuperaValorDiaria(a1.cliente,a.destino,'D',a1.diaria))
                  inner    join co_moeda              g1 on (g.sq_moeda                   = g1.sq_moeda)
                left       join pd_valor_diaria       h  on (h.sq_valor_diaria            = recuperaValorDiaria(a1.cliente,a.destino,'H',a1.diaria))
                  left     join co_moeda              h1 on (h.sq_moeda                   = h1.sq_moeda)
                inner      join pd_valor_diaria       i  on (i.sq_valor_diaria            = recuperaValorDiaria(a1.cliente,a.destino,'V',a1.diaria))
                  inner    join co_moeda              i1 on (i.sq_moeda                   = i1.sq_moeda)
          where a.sq_siw_solicitacao = p_chave
            and a.tipo               = p_tipo;
   Elsif p_restricao = 'DF' Then
      open p_result for
         select count(*) existe
           from pd_deslocamento a
          where sq_siw_solicitacao = p_chave
            and a.passagem         = 'S'
            and a.tipo             = p_tipo
            and sq_cia_transporte is null;
   Elsif p_restricao = 'COTPASS' Then
      -- Recupera trechos para cotação de bilhetes
      open p_result for
         select a.sq_deslocamento, a.sq_siw_solicitacao, a.origem, a.destino, a.sq_cia_transporte, 
                a.saida, a.chegada, a.codigo_cia_transporte, a.valor_trecho, a.codigo_voo, a.passagem, a.sq_bilhete,
                a.aeroporto_origem, a.aeroporto_destino,
                case a.passagem when 'S' then 'Sim' else 'Não' end as nm_passagem,
                a.compromisso, case a.compromisso when 'S' then 'Sim' else 'Não' end as nm_compromisso,
                trunc(a.chegada)-trunc(a.saida) as dias_deslocamento,
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
               inner    join pd_meio_transporte g on (a.sq_meio_transporte = g.sq_meio_transporte)
          where a.sq_siw_solicitacao = p_chave
            and a.tipo               = p_tipo
            and a.passagem           = 'S'
            and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_deslocamento = p_chave_aux));   
   Else
      -- Recupera os trechos da solicitação
      open p_result for
         select a.sq_deslocamento, a.sq_siw_solicitacao, a.origem, a.destino, a.sq_cia_transporte, 
                a.saida, a.chegada, a.codigo_cia_transporte, a.valor_trecho, a.codigo_voo, a.passagem, 
                a.aeroporto_origem, a.aeroporto_destino,
                case a.passagem when 'S' then 'Sim' else 'Não' end as nm_passagem,
                a.compromisso, case a.compromisso when 'S' then 'Sim' else 'Não' end as nm_compromisso,
                trunc(a.chegada)-trunc(a.saida) as dias_deslocamento,
                to_char(a.saida,'dd/mm/yyyy, hh24:mi:ss') as phpdt_saida,
                to_char(a.chegada,'dd/mm/yyyy, hh24:mi:ss') as phpdt_chegada,
                b.sq_cidade cidade_orig, b.co_uf uf_orig, b.sq_pais as pais_orig,
                d.sq_cidade cidade_dest, d.co_uf uf_dest, d.sq_pais as pais_dest,
                case c.padrao when 'S' then b.nome||'-'||b.co_uf else b.nome||' ('||c.nome||')' end as nm_origem,
                case e.padrao when 'S' then d.nome||'-'||d.co_uf else d.nome||' ('||e.nome||')' end as nm_destino,
                f.nome as nm_cia_transporte,
                g.sq_meio_transporte, g.nome as nm_meio_transporte,
                h.sq_bilhete, h.data as dt_bilhete, h.numero as nr_bilhete, h.observacao as obs_bilhete,
                h1.nome as nm_cia_bilhete
           from pd_deslocamento                    a
                inner   join co_cidade             b  on (a.origem                  = b.sq_cidade)
                  inner join co_pais               c  on (b.sq_pais                 = c.sq_pais)
                inner   join co_cidade             d  on (a.destino                 = d.sq_cidade)
                  inner join co_pais               e  on (d.sq_pais                 = e.sq_pais)
               left     join pd_cia_transporte     f  on (a.sq_cia_transporte       = f.sq_cia_transporte)
               left     join pd_meio_transporte    g  on (a.sq_meio_transporte      = g.sq_meio_transporte)
               left     join pd_bilhete            h  on (a.sq_bilhete              = h.sq_bilhete)
                 left   join pd_cia_transporte     h1 on (h.sq_cia_transporte       = h1.sq_cia_transporte)
          where a.sq_siw_solicitacao = p_chave
            and a.tipo               = p_tipo
            and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_deslocamento = p_chave_aux));   
   End If;         
End SP_GetPD_Deslocamento;
/
