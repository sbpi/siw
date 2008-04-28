create or replace function siw.SP_GetBankAccData
   (p_chave      numeric)
  RETURNS character varying AS
$BODY$declare

    p_result     refcursor;

begin
   -- Recupera os dados da conta bancária
   open p_result for
      Select b.sq_banco, b.codigo as agencia, a.numero, a.operacao,
             a.tipo_conta, a.ativo, a.padrao
      from siw.co_pessoa_conta a,
           siw.co_agencia      b
      where a.sq_agencia        = b.sq_agencia
        and a.sq_pessoa_conta   = p_chave;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetBankAccData(p_chave  numeric)OWNER TO siw;
