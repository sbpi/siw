create or replace procedure SP_GetPPADadoFinanc_IS
   (p_chave     in varchar2,
    p_unidade   in varchar2 default null,
    p_ano       in number,
    p_cliente   in number,
    p_restricao in varchar2,
    p_result    out siw.siw.sys_refcursor) is
begin
   If p_restricao = 'VALORFONTE' Then
      -- Recupera os dados financeiros do programa por fonte
      open p_result for 
         select a.cd_programa, b.descricao, 
                c.cd_fonte cd_orcamento, c.nome nm_orcamento,
                b.cd_fonte cd_fonte,     b.nome nm_fonte,
                Nvl(sum(valor_ano_1),0) + Nvl(sum(valor_ano_2),0) + Nvl(sum(valor_ano_3),0) +
                Nvl(sum(valor_ano_4),0) + Nvl(sum(valor_ano_5),0) valor_total,
                sum(valor_ano_1) valor_ano_1, sum(valor_ano_2) valor_ano_2, 
                sum(valor_ano_3) valor_ano_3, sum(valor_ano_4) valor_ano_4,
                sum(valor_ano_5) valor_ano_5
           from is_ppa_dado_financeiro  a,
                is_ppa_fonte b,
                is_ppa_fonte c
          where (a.cd_fonte = b.cd_fonte) 
            and (c.cd_fonte = substr(a.cd_fonte,1,1)||'0000')
            and a.cd_programa = p_chave
            and a.ano         = p_ano
            and a.cliente     = p_cliente
       group by a.cd_programa, b.descricao, c.cd_fonte, c.nome, b.cd_fonte, b.nome;
   Elsif p_restricao = 'VALORTOTAL' Then
      -- Recupera os dados financeiros do programa 
      open p_result for 
         select sum(valor_ano_1) valor_ano_1, sum(valor_ano_2) valor_ano_2, 
                sum(valor_ano_3) valor_ano_3, sum(valor_ano_4) valor_ano_4,
                sum(valor_ano_5) valor_ano_5, a.cd_programa,
                Nvl(sum(valor_ano_1),0) + Nvl(sum(valor_ano_2),0) + Nvl(sum(valor_ano_3),0) +
                Nvl(sum(valor_ano_4),0) + Nvl(sum(valor_ano_5),0) valor_total
           from is_ppa_dado_financeiro a
          where a.cd_programa = p_chave
            and a.ano         = p_ano
            and a.cliente     = p_cliente
       group by a.cd_programa;
   Elsif p_restricao = 'VALORFONTEACAO' Then
      -- Recupera os dados financeiros da acao por fonte
      open p_result for 
         select a.cd_acao, a.descricao_acao, a.cd_unidade,
                e.cd_fonte cd_orcamento, e.nome nm_orcamento,
                d.cd_fonte cd_fonte,     d.nome nm_fonte,
                Nvl(sum(valor_ano_1),0) + Nvl(sum(valor_ano_2),0) + Nvl(sum(valor_ano_3),0) +
                Nvl(sum(valor_ano_4),0) + Nvl(sum(valor_ano_5),0) valor_total,
                sum(valor_ano_1) valor_ano_1, sum(valor_ano_2) valor_ano_2, 
                sum(valor_ano_3) valor_ano_3, sum(valor_ano_4) valor_ano_4,
                sum(valor_ano_5) valor_ano_5
           from is_sig_acao                       a,
                is_ppa_acao            b,
                is_ppa_dado_financeiro c,
                is_ppa_fonte           d,
                is_ppa_fonte           e
          where (a.cd_programa = b.cd_programa  and
                 a.cd_acao     = b.cd_acao      and
                 a.cd_unidade  = b.cd_unidade   and
                 a.cliente     = b.cliente      and
                 a.ano         = b.ano)
            and (b.cd_programa = b.cd_programa  and
                 b.cd_acao_ppa = c.cd_acao_ppa  and
                 b.cliente     = c.cliente      and
                 b.ano         = c.ano)
            and (d.cd_fonte = c.cd_fonte)
            and (e.cd_fonte = substr(c.cd_fonte,1,1)||'0000')
            and a.cd_acao     = p_chave
            and a.cd_unidade  = p_unidade
            and a.ano         = p_ano
            and a.cliente     = p_cliente
       group by a.cd_acao, a.descricao_acao, a.cd_unidade, e.cd_fonte, e.nome, d.cd_fonte, d.nome;
   Elsif p_restricao = 'VALORTOTALACAO' Then
      -- Recupera os dados financeiros da acao
      open p_result for 
         select a.cd_acao, a.descricao_acao, a.cd_unidade,
                Nvl(sum(valor_ano_1),0) + Nvl(sum(valor_ano_2),0) + Nvl(sum(valor_ano_3),0) +
                Nvl(sum(valor_ano_4),0) + Nvl(sum(valor_ano_5),0) valor_total,
                sum(valor_ano_1) valor_ano_1, sum(valor_ano_2) valor_ano_2, 
                sum(valor_ano_3) valor_ano_3, sum(valor_ano_4) valor_ano_4,
                sum(valor_ano_5) valor_ano_5
           from is_sig_acao                       a,
                is_ppa_acao            b, 
                is_ppa_dado_financeiro c 
          where (a.cd_programa = b.cd_programa  and
                 a.cd_acao     = b.cd_acao      and
                 a.cd_unidade  = b.cd_unidade   and
                 a.cliente     = b.cliente      and
                 a.ano         = b.ano)
            and (b.cd_programa = b.cd_programa  and
                 b.cd_acao_ppa = c.cd_acao_ppa  and
                 b.cliente     = c.cliente      and
                 b.ano         = c.ano)
            and a.cd_acao     = p_chave
            and a.cd_unidade  = p_unidade
            and a.ano         = p_ano
            and a.cliente     = p_cliente
       group by a.cd_acao, a.descricao_acao, a.cd_unidade;
   End If;
end SP_GetPPADadoFinanc_IS;
/

