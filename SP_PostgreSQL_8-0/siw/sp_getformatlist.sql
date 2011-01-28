create or replace FUNCTION SP_GetFormatList
   (p_tipo       varchar,
    p_nome       varchar,
    p_ativo      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os bancos existentes
   open p_result for
      select a.ordem, a.sq_formacao, a.nome, a.ativo, b.tipo,
             case a.ativo when 'S' then 'Sim' else 'Não' end ativodesc
        from co_formacao   a
             inner join (select sq_formacao, 
                                case tipo when '1' then 'Acadêmica' 
                                          when '2' then 'Técnica'
                                          else 'Prod.Cient.'
                                end tipo
                           from co_formacao
                         ) b on a.sq_formacao = b.sq_formacao
       where (p_tipo  is null or (p_tipo  is not null and b.tipo = p_tipo))
         and (p_nome  is null or (p_nome  is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;