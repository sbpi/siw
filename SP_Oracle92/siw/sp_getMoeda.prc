create or replace procedure SP_GetMoeda
   (p_chave     in  number   default null,
    p_restricao in  varchar2 default null,
    p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_sigla     in  varchar2 default null,
    p_serie     in  number   default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera as unidades monetárias existentes
   open p_result for 
      select a.sq_moeda, a.codigo, a.nome, a.sigla, a.simbolo, a.tipo, a.exclusao_ptax, a.ativo, 
             a.bc_serie_compra, a.bc_serie_venda,
             case a.ativo  when 'S' then 'Sim' else 'Não' end as nm_ativo 
        from co_moeda              a
       where (p_chave      is null or (p_chave is not null and a.sq_moeda = p_chave))
         and (p_ativo      is null or (p_ativo is not null and a.ativo = p_ativo))
         and (p_sigla      is null or (p_sigla is not null and a.sigla = p_sigla))
         and (p_serie      is null or (p_serie is not null and (a.bc_serie_compra = p_serie or a.bc_serie_venda = p_serie)))
         and (p_restricao  is null or 
              (p_restricao is not null and
               ((p_restricao = 'ATIVO' and a.ativo = 'S') or
                (p_restricao = 'PDRB' and a.exclusao_ptax is null)
               )
              )
             );
end SP_GetMoeda;
/
