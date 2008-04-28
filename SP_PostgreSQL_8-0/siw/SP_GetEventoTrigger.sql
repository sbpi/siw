CREATE OR REPLACE FUNCTION siw.SP_GetEventoTrigger
   (p_chave    numeric)
   
      RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;

begin
   -- Recupera os eventos de trigger existentes
   open p_result for
      select a.sq_evento as chave, a.nome, a.descricao
        from siw.dc_evento a
       where ((p_chave is null) or (p_chave is not null and a.sq_evento = p_chave));
       return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEventoTrigger
   (p_chave    numeric) OWNER TO siw;
