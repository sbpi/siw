create or replace procedure SP_GetMoeda
   (p_chave     in  number   default null,
    p_restricao in  varchar2 default null,
    p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_sigla     in  varchar2 default null,
    p_result    out sys_refcursor) is
begin
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
               (p_restricao = 'ATIVO' and a.ativo = 'S')
              )
             );
end SP_GetMoeda;
/
