create or replace function siw.SP_GetBankList
   (p_codigo   varchar,
    p_nome     varchar,
    p_ativo    varchar)
  RETURNS character varying AS
$BODY$declare
    p_result   refcursor;
begin
   -- Recupera os bancos existentes
   open p_result for 
      select sq_banco, codigo, nome, ativo, codigo||' - '||nome as descricao, padrao, exige_operacao
        from siw.co_banco a 
       where (p_nome   is null or (p_nome   is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_codigo is null or (p_codigo is not null and codigo = p_codigo))
         and (p_ativo  is null or (p_ativo  is not null and ativo  = p_ativo))
      order by padrao desc, codigo;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetBankList
   (p_codigo   varchar,
    p_nome     varchar,
    p_ativo    varchar) OWNER TO siw;
