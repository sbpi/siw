CREATE OR REPLACE FUNCTION siw.SP_GetCargo(p_cliente numeric)
  RETURNS character varying AS
$BODY$declare

 p_result     refcursor;
begin
   -- Recupera os dados do centro de ccusto informado
   open p_result for
      select a.sq_cc_pai, a.nome, a.sigla, a.descricao, a.ativo, a.receita, a.regular
        from siw.ct_cc a
       where sq_cc =p_cliente;

  return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCargo(numeric) OWNER TO siw;
