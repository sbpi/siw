create or replace FUNCTION SP_GetMoeda
   (p_chave      numeric,
    p_restricao  varchar,
    p_nome       varchar,
    p_ativo      varchar,
    p_sigla      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as unidades monetárias existentes
   open p_result for 
      select a.sq_moeda, a.codigo, a.nome, a.sigla, a.simbolo, a.tipo, a.exclusao_ptax, a.ativo,
             case a.ativo  when 'S' then 'Sim' else 'Não' end as nm_ativo 
        from co_moeda              a
       where (p_chave      is null or (p_chave is not null and a.sq_moeda = p_chave))
         and (p_ativo      is null or (p_ativo is not null and a.ativo = p_ativo))
         and (p_sigla      is null or (p_sigla is not null and a.sigla = p_sigla))
         and (p_restricao  is null or 
              (p_restricao is not null and
               ((p_restricao = 'ATIVO' and a.ativo = 'S') or
                (p_restricao = 'PDRB' and a.exclusao_ptax is null)
               )
              )
             );
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;