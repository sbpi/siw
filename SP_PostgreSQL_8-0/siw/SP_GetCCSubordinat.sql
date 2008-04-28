CREATE OR REPLACE FUNCTION siw.SP_GetCCSubordinat
   (p_cliente   numeric,
    p_sqcc      numeric,
    p_restricao varchar)
  RETURNS character varying AS
$BODY$
DECLARE

    p_result    refcursor;

begin
   -- Recupera a os centros de custo aos quais o atual pode ser subordinado
   -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
   If upper(p_restricao) = 'TODOS' Then
      open p_result for
         select a.sq_cc as sq_cc,a.nome
           from siw.ct_cc   a
          where a.cliente = p_cliente
         order by a.nome;
   Else
      open p_result for
         select a.sq_cc as sq_cc,a.nome
           from siw.ct_cc   a
          where a.cliente = p_cliente
            and a.sq_cc not in (select a.sq_cc
                                  from siw.ct_cc a
                                 where a.cliente   = p_cliente
                                start with a.sq_cc = p_sqcc
                                connect by prior a.sq_cc = a.sq_cc_pai
                               )
         order by a.nome;
   End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCCSubordinat
   (p_cliente   numeric,
    p_sqcc      numeric,
    p_restricao varchar) OWNER TO siw;
