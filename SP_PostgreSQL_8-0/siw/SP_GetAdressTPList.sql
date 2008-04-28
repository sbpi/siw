CREATE OR REPLACE FUNCTION siw.SP_GetAdressTPList
   (p_tipo_pessoa  varchar,
    p_nome        varchar,
    p_ativo       varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera as deficiências existentes
   open p_result for
      select a.sq_tipo_endereco, a.nome, a.padrao,
             case a.padrao when 'S' then 'Sim' else 'Não' end as padraodesc,
             case a.email when 'S' then 'Sim' else 'Não' end as email,
             case a.internet when 'S' then 'Sim' else 'Não' end as internet,
             a.ativo,
             case a.ativo when 'S' then 'Sim' else 'Não' end as ativodesc, b.nome as sq_tipo_pessoa
        from siw.co_tipo_endereco a, siw.co_tipo_pessoa b
      where a.sq_tipo_pessoa = b.sq_tipo_pessoa
        and (p_tipo_pessoa is null or (p_tipo_pessoa is not null and b.nome  = p_tipo_pessoa))
        and (p_nome        is null or (p_nome        is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
        and (p_ativo       is null or (p_ativo       is not null and a.ativo = p_ativo));
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetAdressTPList
   (p_tipo_pessoa  varchar,
    p_nome        varchar,
    p_ativo       varchar) OWNER TO siw;
