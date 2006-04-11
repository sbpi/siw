create or replace procedure SP_GetPPADadoFinanc_IS
   (p_chave     in varchar2,
    p_unidade   in varchar2 default null,
    p_ano       in number,
    p_cliente   in number,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
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
           from is_ppa_dado_financeiro  a
                inner join is_ppa_fonte b on (a.cd_fonte = b.cd_fonte)
                inner join is_ppa_fonte c on (c.cd_fonte = substr(a.cd_fonte,1,1)||'0000')
          where a.cd_programa = p_chave
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
         select a.cd_acao, a.descricao_acao, a.cd_unidade, a.cd_programa,
                e.cd_fonte cd_orcamento, e.nome nm_orcamento,
                d.cd_fonte cd_fonte,     d.nome nm_fonte,
                Nvl(sum(valor_ano_1),0) + Nvl(sum(valor_ano_2),0) + Nvl(sum(valor_ano_3),0) +
                Nvl(sum(valor_ano_4),0) + Nvl(sum(valor_ano_5),0) valor_total,
                sum(valor_ano_1) valor_ano_1, sum(valor_ano_2) valor_ano_2, 
                sum(valor_ano_3) valor_ano_3, sum(valor_ano_4) valor_ano_4,
                sum(valor_ano_5) valor_ano_5
           from is_sig_acao                       a
                inner join is_ppa_acao            b on (a.cd_programa = b.cd_programa  and
                                                        a.cd_acao     = b.cd_acao      and
                                                        a.cd_unidade  = b.cd_unidade   and
                                                        a.cliente     = b.cliente      and
                                                        a.ano         = b.ano)
                inner join is_ppa_dado_financeiro c on (b.cd_programa = b.cd_programa  and
                                                        b.cd_acao_ppa = c.cd_acao_ppa  and
                                                        b.cliente     = c.cliente      and
                                                        b.ano         = c.ano)
                inner join is_ppa_fonte           d on (d.cd_fonte = c.cd_fonte)
                inner join is_ppa_fonte           e on (e.cd_fonte = substr(c.cd_fonte,1,1)||'0000')
          where a.cd_acao     = p_chave
            and a.cd_unidade  = p_unidade
            and a.ano         = p_ano
            and a.cliente     = p_cliente
       group by a.cd_acao, a.descricao_acao, a.cd_unidade, a.cd_programa, e.cd_fonte, e.nome, d.cd_fonte, d.nome;
   Elsif p_restricao = 'VALORTOTALACAO' Then
      -- Recupera os dados financeiros da acao
      open p_result for 
         select a.cd_acao, a.descricao_acao, a.cd_unidade,
                Nvl(sum(valor_ano_1),0) + Nvl(sum(valor_ano_2),0) + Nvl(sum(valor_ano_3),0) +
                Nvl(sum(valor_ano_4),0) + Nvl(sum(valor_ano_5),0) valor_total,
                sum(valor_ano_1) valor_ano_1, sum(valor_ano_2) valor_ano_2, 
                sum(valor_ano_3) valor_ano_3, sum(valor_ano_4) valor_ano_4,
                sum(valor_ano_5) valor_ano_5
           from is_sig_acao                       a
                inner join is_ppa_acao            b on (a.cd_programa = b.cd_programa  and
                                                        a.cd_acao     = b.cd_acao      and
                                                        a.cd_unidade  = b.cd_unidade   and
                                                        a.cliente     = b.cliente      and
                                                        a.ano         = b.ano)
                inner join is_ppa_dado_financeiro c on (b.cd_programa = c.cd_programa  and
                                                        b.cd_acao_ppa = c.cd_acao_ppa  and
                                                        b.cliente     = c.cliente      and
                                                        b.ano         = c.ano)
          where a.cd_acao     = p_chave
            and a.cd_unidade  = p_unidade
            and a.ano         = p_ano
            and a.cliente     = p_cliente
       group by a.cd_acao, a.descricao_acao, a.cd_unidade;
   Elsif p_restricao = 'VALORTOTALMENSAL' Then
      -- Recupera os dados financeiros da acao da tabela do SIGPLAN
      open p_result for       
         select distinct a.cd_acao, a.descricao_acao, a.cd_unidade,
                Nvl(sum(b.cron_ini_mes_1),0)  cron_ini_mes_1,  Nvl(sum(b.cron_ini_mes_2),0)  cron_ini_mes_2,  Nvl(sum(b.cron_ini_mes_3),0)  cron_ini_mes_3,
                Nvl(sum(b.cron_ini_mes_4),0)  cron_ini_mes_4,  Nvl(sum(b.cron_ini_mes_5),0)  cron_ini_mes_5,  Nvl(sum(b.cron_ini_mes_6),0)  cron_ini_mes_6,
                Nvl(sum(b.cron_ini_mes_7),0)  cron_ini_mes_7,  Nvl(sum(b.cron_ini_mes_8),0)  cron_ini_mes_8,  Nvl(sum(b.cron_ini_mes_9),0)  cron_ini_mes_9,
                Nvl(sum(b.cron_ini_mes_10),0) cron_ini_mes_10, Nvl(sum(b.cron_ini_mes_11),0) cron_ini_mes_11, Nvl(sum(b.cron_ini_mes_12),0) cron_ini_mes_12,
                Nvl(sum(b.cron_mes_1),0)  cron_mes_1,  Nvl(sum(b.cron_mes_2),0)  cron_mes_2,  Nvl(sum(b.cron_mes_3),0)  cron_mes_3,
                Nvl(sum(b.cron_mes_4),0)  cron_mes_4,  Nvl(sum(b.cron_mes_5),0)  cron_mes_5,  Nvl(sum(b.cron_mes_6),0)  cron_mes_6,
                Nvl(sum(b.cron_mes_7),0)  cron_mes_7,  Nvl(sum(b.cron_mes_8),0)  cron_mes_8,  Nvl(sum(b.cron_mes_9),0)  cron_mes_9,
                Nvl(sum(b.cron_mes_10),0) cron_mes_10, Nvl(sum(b.cron_mes_11),0) cron_mes_11, Nvl(sum(b.cron_mes_12),0) cron_mes_12,
                Nvl(sum(b.real_mes_1),0)  real_mes_1,  Nvl(sum(b.real_mes_2),0)  real_mes_2,  Nvl(sum(b.real_mes_3),0)  real_mes_3,
                Nvl(sum(b.real_mes_4),0)  real_mes_4,  Nvl(sum(b.real_mes_5),0)  real_mes_5,  Nvl(sum(b.real_mes_6),0)  real_mes_6,
                Nvl(sum(b.real_mes_7),0)  real_mes_7,  Nvl(sum(b.real_mes_8),0)  real_mes_8,  Nvl(sum(b.real_mes_9),0)  real_mes_9,
                Nvl(sum(b.real_mes_10),0) real_mes_10, Nvl(sum(b.real_mes_11),0) real_mes_11, Nvl(sum(b.real_mes_12),0) real_mes_12,                
                Nvl(sum(b.cron_ini_mes_1),0)+Nvl(sum(b.cron_ini_mes_2),0)+Nvl(sum(b.cron_ini_mes_3),0)+Nvl(sum(b.cron_ini_mes_4),0)+Nvl(sum(b.cron_ini_mes_5),0)+Nvl(sum(b.cron_ini_mes_6),0)+Nvl(sum(b.cron_ini_mes_7),0)+Nvl(sum(b.cron_ini_mes_8),0)+Nvl(sum(b.cron_ini_mes_9),0)+Nvl(sum(b.cron_ini_mes_10),0)+Nvl(sum(b.cron_ini_mes_11),0)+Nvl(sum(b.cron_ini_mes_12),0) cron_ini_total,
                Nvl(sum(b.cron_mes_1),0)+Nvl(sum(b.cron_mes_2),0)+Nvl(sum(b.cron_mes_3),0)+Nvl(sum(b.cron_mes_4),0)+Nvl(sum(b.cron_mes_5),0)+Nvl(sum(b.cron_mes_6),0)+Nvl(sum(b.cron_mes_7),0)+Nvl(sum(b.cron_mes_8),0)+Nvl(sum(b.cron_mes_9),0)+Nvl(sum(b.cron_mes_10),0)+Nvl(sum(b.cron_mes_11),0)+Nvl(sum(b.cron_mes_12),0) cron_mes_total,
                Nvl(sum(b.real_mes_1),0)+Nvl(sum(b.real_mes_2),0)+Nvl(sum(b.real_mes_3),0)+Nvl(sum(b.real_mes_4),0)+Nvl(sum(b.real_mes_5),0)+Nvl(sum(b.real_mes_6),0)+Nvl(sum(b.real_mes_7),0)+Nvl(sum(b.real_mes_8),0)+Nvl(sum(b.real_mes_9),0)+Nvl(sum(b.real_mes_10),0)+Nvl(sum(b.real_mes_11),0)+Nvl(sum(b.real_mes_12),0) real_mes_total,
                Nvl(sum(b.previsao_ano),0) previsao_ano, Nvl(sum(b.real_ano),0) real_ano, Nvl(sum(b.cron_ini_ano),0) cron_ini_ano, Nvl(sum(b.atual_ano),0) atual_ano, Nvl(sum(b.cron_ano),0) cron_ano
           from is_sig_acao                       a
                inner join is_sig_dado_financeiro b on (a.cd_programa = b.cd_programa  and
                                                        a.cd_acao     = b.cd_acao      and
                                                        a.cd_subacao  = b.cd_subacao   and
                                                        a.cliente     = b.cliente      and
                                                        a.ano         = b.ano)
          where a.cd_acao     = p_chave
            and a.cd_unidade  = p_unidade
            and a.ano         = p_ano
            and a.cliente     = p_cliente
       group by a.cd_acao, a.descricao_acao, a.cd_unidade;   
   End If;
end SP_GetPPADadoFinanc_IS;
/
