create or replace procedure SP_GetOrPrioridade
   (p_chave           in  number  default null,
    p_cliente         in  number,
    p_sq_orprioridade in number   default null,
    p_responsavel     in varchar2 default null,
    p_mpog            in varchar2 default null,
    p_relevante       in varchar2 default null,
    p_result          out siw.sys_refcursor) is
begin
   -- Recupera as iniciativas prioritárias do Governo
   open p_result for
       select a.sq_orprioridade chave, a.codigo, a.nome,  b.sq_orprioridade existe,
             a.cliente, a.responsavel, a.telefone, a.email, a.ativo, a.padrao, a.ordem,
             decode(a.ativo,'S','Sim','Não') nm_ativo,
             decode(a.padrao,'S','Sim','Não') nm_padrao,
             c.sq_siw_solicitacao,
             d.sq_acao_ppa, d.sq_acao_ppa_pai, d.codigo cd_acao, d.nome nm_acao,
             d.responsavel acao_resp, d.telefone acao_tel, d.email acao_email,
             d.selecionada_mpog, d.selecionada_relevante,
             d.aprovado, d.saldo, d.empenhado, d.liquidado, d.liquidar,
             e.nome nm_acao_pai, e.codigo cd_pai, f.titulo
        from or_prioridade a,
             or_acao_prioridade b,
             or_acao            c,
             pj_projeto         f,
             or_acao_ppa        d,
             or_acao_ppa        e
       where a.sq_orprioridade    = b.sq_orprioridade (+)
             and (( p_chave is null) or (p_chave is not null and b.sq_siw_solicitacao = p_chave))
         and a.sq_orprioridade    = c.sq_orprioridade (+)
         and c.sq_siw_solicitacao = f.sq_siw_solicitacao (+)
         and c.sq_acao_ppa        = d.sq_acao_ppa (+)
         and d.sq_acao_ppa_pai    = e.sq_acao_ppa (+)
         and a.cliente = p_cliente
         and ((p_sq_orprioridade is null) or (p_sq_orprioridade is not null and a.sq_orprioridade = p_sq_orprioridade))
         and ((p_responsavel is null) or (p_responsavel is not null and acentos(a.responsavel)  like '%'||acentos(p_responsavel)||'%'))
         and ((p_mpog        is null) or (p_mpog        is not null and d.selecionada_mpog      = p_mpog))
         and ((p_relevante   is null) or (p_relevante   is not null and d.selecionada_relevante = p_relevante))
       order by chave, titulo;
end SP_GetOrPrioridade;
/

