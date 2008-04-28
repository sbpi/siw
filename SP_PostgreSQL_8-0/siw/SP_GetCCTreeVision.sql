CREATE OR REPLACE FUNCTION siw.SP_GetCCTreeVision
   (p_cliente   numeric,
    p_sq_pessoa numeric,
    p_sq_menu   numeric,
    p_restricao varchar)
  RETURNS character varying AS
$BODY$
DECLARE
   
    p_result   refcursor;
   
begin
   -- Recupera a �rvore de centros de custo
   If p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, Nvl(c.sq_cc,0) as existe, Nvl(b.Filho,0) as Filho
              from siw.ct_cc a
                   left outer join (select sq_cc_pai,count(*) as Filho
                                      from siw.ct_cc x
                                     where cliente = p_cliente
                                    group by sq_cc_pai) b      on (a.sq_cc = b.sq_cc_pai)
                   left outer join siw.siw_pessoa_cc        c      on (a.sq_cc     = c.sq_cc and
                                                                   c.sq_pessoa = Nvl(p_sq_pessoa,0) and
                                                                   c.sq_menu   = Nvl(p_sq_menu,0))
             where a.cliente   = p_cliente
               and a.sq_cc_pai is null
             order by a.receita, a.nome;
      Else
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, Nvl(c.sq_cc,0) as existe, Nvl(b.Filho,0) as Filho
              from siw.ct_cc a
                   left outer join (select sq_cc_pai,count(*) as Filho
                                      from siw.t_cc x
                                     where cliente = p_cliente
                                    group by sq_cc_pai) b on (a.sq_cc = b.sq_cc_pai)
                   left outer join siw.siw_pessoa_cc        c      on (a.sq_cc     = c.sq_cc and
                                                                   c.sq_pessoa = Nvl(p_sq_pessoa,0) and
                                                                   c.sq_menu   = Nvl(p_sq_menu,0))
             where a.cliente      = p_cliente
               and a.sq_cc_pai    = p_restricao
             order by a.receita, a.nome;
      End If;
    End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCCTreeVision
   (p_cliente   numeric,
    p_sq_pessoa numeric,
    p_sq_menu   numeric,
    p_restricao varchar) OWNER TO siw;
