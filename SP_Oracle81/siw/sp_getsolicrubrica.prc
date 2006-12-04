create or replace procedure SP_GetSolicRubrica
   (p_chave                in number,
    p_chave_aux            in number    default null,
    p_ativo                in varchar2  default null,
    p_sq_rubrica_destino   in number    default null,
    p_aplicacao_financeira in varchar2  default null,
    p_restricao            in varchar2  default null,
    p_result    out        siw.sys_refcursor
   ) is
begin
   If p_restricao is null Then
      open p_result for 
         select a.sq_projeto_rubrica, a.sq_cc, a.codigo, a.nome, a.descricao, a.ativo,
                a.valor_inicial, a.entrada_prevista, a.entrada_real, (a.entrada_prevista - a.entrada_real) entrada_pendente,
                a.saida_prevista, a.saida_real, (a.saida_prevista-a.saida_real) saida_pendente,
                decode(a.ativo,'S','Sim','Não') nm_ativo,
                decode(a.aplicacao_financeira,'S','Sim','Não') nm_aplicacao_financeira,
                b.nome nm_cc, a.aplicacao_financeira
           from pj_rubrica a,
                ct_cc      b
          where (a.sq_cc = b.sq_cc)
            and (p_chave                is null or (p_chave                is not null and a.sq_siw_solicitacao   = p_chave))
            and (p_chave_aux            is null or (p_chave_aux            is not null and a.sq_projeto_rubrica   = p_chave_aux))
            and (p_ativo                is null or (p_ativo                is not null and a.ativo                = p_ativo))
            and (p_sq_rubrica_destino   is null or (p_sq_rubrica_destino   is not null and a.sq_projeto_rubrica   <> p_sq_rubrica_destino))
            and (p_aplicacao_financeira is null or (p_aplicacao_financeira is not null and a.aplicacao_financeira = p_aplicacao_financeira));
   Elsif p_restricao = 'FICHA' Then
     open p_result for    
        select sum(a.valor) valor, 
               c.vencimento, c.codigo_interno cd_lancamento, c.sq_siw_solicitacao sq_lancamento, c.tipo tipo_rubrica,
               to_char(c.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
               d.descricao nm_lancamento, e.sigla sg_lancamento_menu,
               decode(c.tipo,5,e.nome,4,'Entradas',3,'Atualização de aplicação',2,'Transferência entre rubricas',1,'Dotação inicial') operacao,
               f.nome nm_rubrica, f.codigo codigo_rubrica,
               g.titulo nm_projeto, g.sq_siw_solicitacao sq_projeto, i.codigo_interno cd_acordo, i.sq_siw_solicitacao sq_acordo,
               l.nome nm_label, l.sigla sg, m.sigla sg_tramite
          from fn_lancamento_rubrica a,
               fn_lancamento_doc     b,
               fn_lancamento         c,
               siw_solicitacao       d,
               siw_menu              e,
               siw_tramite           m,
               pj_rubrica            f,
               pj_projeto            g,
               siw_solicitacao       h,
               ac_acordo             i,
               siw_solicitacao       j,
               siw_menu              l
         where (a.sq_lancamento_doc  = b.sq_lancamento_doc)
           and (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
           and (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
           and (d.sq_menu            = e.sq_menu)           
           and (d.sq_siw_tramite     = m.sq_siw_tramite)
           and (a.sq_rubrica_origem  = f.sq_projeto_rubrica)
           and (f.sq_siw_solicitacao = g.sq_siw_solicitacao)
           and (g.sq_siw_solicitacao = h.sq_siw_solicitacao (+))
           and (h.sq_solic_pai       = i.sq_siw_solicitacao (+))
           and (i.sq_siw_solicitacao = j.sq_siw_solicitacao (+))
           and (j.sq_menu            = l.sq_menu            (+))
           and a.sq_rubrica_origem = p_chave_aux
           and m.sigla             <> 'CA'
         group by c.vencimento, c.codigo_interno, c.sq_siw_solicitacao, c.tipo, e.nome, g.titulo, g.sq_siw_solicitacao,
                  i.codigo_interno, i.sq_siw_solicitacao, f.nome, f.codigo, d.descricao, l.nome, l.sigla, m.sigla,
                  e.sigla
     UNION
        select sum(a.valor_total) valor, 
               c.vencimento, c.codigo_interno cd_lancamento, c.sq_siw_solicitacao sq_lancamento, c.tipo tipo_rubrica,
               to_char(c.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
               d.descricao nm_lancamento,  e.sigla sg_lancamento_menu,
               decode(c.tipo,5,e.nome,4,'Entradas',3,'Atualização de aplicação',2,'Transferência entre rubricas',1,'Dotação inicial') operacao,
               f.nome nm_rubrica, f.codigo codigo_rubrica,
               g.titulo nm_projeto, g.sq_siw_solicitacao sq_projeto, i.codigo_interno cd_acordo, i.sq_siw_solicitacao sq_acordo,
               l.nome nm_label, l.sigla sg, m.sigla sg_tramite
          from fn_documento_item a,
               fn_lancamento_doc b,
               fn_lancamento     c,
               siw_solicitacao   d,
               siw_menu          e,
               siw_tramite       m,
               pj_rubrica        f,
               pj_projeto        g,
               siw_solicitacao   h,
               ac_acordo         i,
               siw_solicitacao   j,
               siw_menu          l
         where a.sq_projeto_rubrica  = p_chave_aux
           and (a.sq_lancamento_doc  = b.sq_lancamento_doc)
           and (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
           and (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
           and (d.sq_siw_tramite     = m.sq_siw_tramite)
           and (a.sq_projeto_rubrica = f.sq_projeto_rubrica)
           and (f.sq_siw_solicitacao = g.sq_siw_solicitacao)
           and (g.sq_siw_solicitacao = h.sq_siw_solicitacao (+))
           and (h.sq_solic_pai       = i.sq_siw_solicitacao (+))
           and (i.sq_siw_solicitacao = j.sq_siw_solicitacao (+))
           and (j.sq_menu            = l.sq_menu            (+))
           and (d.sq_menu            = e.sq_menu)
           and m.sigla             <> 'CA'
         group by c.vencimento, c.codigo_interno, c.sq_siw_solicitacao, c.tipo, e.nome, g.titulo, g.sq_siw_solicitacao,
                  i.codigo_interno, i.sq_siw_solicitacao, f.nome, f.codigo, d.descricao, l.nome, l.sigla, m.sigla,
                  e.sigla;
   End If;  
End SP_GetSolicRubrica;
/
