create or replace procedure SP_GetSolicGR
   (p_menu      in number,
    p_pessoa    in number,
    p_restricao in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   If Substr(p_restricao,1,4) = 'GRDM' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for
         select a.sq_unidade, a.nome, a.sigla,
                b.qt_solic,
                c.vl_previsto,
                d.qt_conc,
                e.vl_faixa1,
                f.vl_faixa2,
                g.vl_faixa3
           from eo_unidade a,
                (select sq_unidade, sq_menu, count(*) qt_solic from siw_solicitacao group by sq_unidade, sq_menu) b,
                (select sq_unidade, sq_menu, sum(valor) vl_previsto from siw_solicitacao group by sq_unidade, sq_menu) c,
                (select sq_unidade, sq_menu, conclusao, count(*) qt_conc from siw_solicitacao group by sq_unidade, sq_menu, conclusao) d,
                (select sq_unidade, valor, sq_menu, count(*) vl_faixa1 from siw_solicitacao group by sq_unidade, valor, sq_menu) e,
                (select sq_unidade, valor, sq_menu, count(*) vl_faixa2 from siw_solicitacao group by sq_unidade, valor, sq_menu) f,
                (select sq_unidade, valor, sq_menu, count(*) vl_faixa3 from siw_solicitacao group by sq_unidade, valor, sq_menu) g
          where a.sq_unidade = b.sq_unidade
            and b.sq_menu    = p_menu
            and a.sq_unidade = c.sq_unidade
            and c.sq_menu    = p_menu
            and a.sq_unidade = d.sq_unidade
            and d.conclusao  is not null
            and d.sq_menu    = p_menu
            and a.sq_unidade = e.sq_unidade
            and e.valor      between 0 and 100
            and e.sq_menu    = p_menu
            and a.sq_unidade = f.sq_unidade
            and f.valor      between 101 and 200
            and f.sq_menu    = p_menu
            and a.sq_unidade = g.sq_unidade
            and g.valor      > 201
            and g.sq_menu    = p_menu
            and a.sq_pessoa = p_pessoa;
   Elsif Substr(p_restricao,1,4) = 'GRPR' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for
         select a.sq_unidade, a.nome, a.sigla,
                b.qt_solic,
                c.vl_previsto,
                d.qt_conc,
                e.vl_faixa1,
                f.vl_faixa2,
                g.vl_faixa3
           from eo_unidade a,
                (select sq_unidade, sq_menu, count(*) qt_solic from siw_solicitacao group by sq_unidade, sq_menu) b,
                (select sq_unidade, sq_menu, sum(valor) vl_previsto from siw_solicitacao group by sq_unidade, sq_menu) c,
                (select sq_unidade, conclusao, sq_menu, count(*) qt_conc from siw_solicitacao group by sq_unidade, conclusao, sq_menu) d,
                (select sq_unidade, valor, sq_menu, count(*) vl_faixa1 from siw_solicitacao group by sq_unidade, valor, sq_menu) e,
                (select sq_unidade, valor, sq_menu, count(*) vl_faixa2 from siw_solicitacao group by sq_unidade, valor, sq_menu) f,
                (select sq_unidade, valor, sq_menu, count(*) vl_faixa3 from siw_solicitacao group by sq_unidade, valor, sq_menu) g
          where a.sq_unidade = b.sq_unidade
            and b.sq_menu    = p_menu
            and a.sq_unidade = c.sq_unidade
            and c.sq_menu    = p_menu
            and a.sq_unidade = d.sq_unidade
            and d.conclusao  is not null
            and d.sq_menu    = p_menu
            and a.sq_unidade = e.sq_unidade
            and e.valor      between 0 and 100
            and e.sq_menu    = p_menu
            and a.sq_unidade = f.sq_unidade
            and f.valor      between 101 and 200
            and f.sq_menu    = p_menu
            and a.sq_unidade = g.sq_unidade
            and g.valor      > 201
            and g.sq_menu    = p_menu
            and a.sq_pessoa = p_pessoa;
   End If;
end SP_GetSolicGR;
/

