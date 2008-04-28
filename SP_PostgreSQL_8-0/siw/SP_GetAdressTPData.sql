CREATE OR REPLACE FUNCTION siw.SP_GetAdressTPData
   (p_sq_tipo_endereco numeric)

   RETURNS character varying AS
$BODY$declare

    p_result           refcursor;
begin
   -- Recupera os dados do tipo de endereço
   open p_result for
      select * from siw.co_tipo_endereco where sq_tipo_endereco = p_sq_tipo_endereco;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetAdressTPData(p_sq_tipo_endereco numeric) OWNER TO siw;
