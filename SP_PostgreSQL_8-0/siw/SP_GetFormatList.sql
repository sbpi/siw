CREATE OR REPLACE FUNCTION siw.SP_GetFormatList
   (p_tipo      varchar,
    p_nome      varchar,
    p_ativo     varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os bancos existentes
   open p_result for
      select a.ordem, a.sq_formacao, a.nome, a.ativo, b.tipo,
             case a.ativo when 'S' then 'Sim' else 'Não' end as ativodesc
        from siw.co_formacao   a
             inner join (select sq_formacao,
                                case tipo when '1' then 'Acadêmica'
                                          when '2' then 'Técnica'
                                          else 'Prod.Cient.'
                                end as tipo
                           from siw.co_formacao
                         ) b on a.sq_formacao = b.sq_formacao
       where (p_tipo  is null or (p_tipo  is not null and b.tipo = p_tipo))
         and (p_nome  is null or (p_nome  is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetFormatList
   (p_tipo      varchar,
    p_nome      varchar,
    p_ativo     varchar) OWNER TO siw;

