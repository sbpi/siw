CREATE OR REPLACE FUNCTION siw.SP_GetFoneData
   (p_chave       numeric)
   RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os dados do endereco informado
   open p_result for
      select b.*, c.sq_pais, c.co_uf
      from siw.co_pessoa_telefone b, siw.co_cidade c
      where b.sq_cidade          = c.sq_cidade
        and b.sq_pessoa_telefone = p_chave;
        return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetFoneData
   (p_chave       numeric) OWNER TO siw;
