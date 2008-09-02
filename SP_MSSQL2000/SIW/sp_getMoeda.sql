alter procedure Sp_GetMoeda
   (@p_chave     int   = null,
    @p_restricao varchar(30) =  null,
    @p_nome      varchar(60) =  null,
    @p_ativo     varchar(1)  =  null,
    @p_sigla     varchar(3)  =  null
) as
begin
   -- Recupera as unidades monetárias existentes

      select a.sq_moeda, a.codigo, a.nome, a.sigla, a.simbolo, a.tipo, a.exclusao_ptax, a.ativo,
             case a.ativo  when 'S' then 'Sim' else 'Não' end as nm_ativo 
        from co_moeda              a
       where (@p_chave      is null or (@p_chave is not null and a.sq_moeda = @p_chave))
         and (@p_ativo      is null or (@p_ativo is not null and a.ativo = @p_ativo))
         and (@p_sigla      is null or (@p_sigla is not null and a.sigla = @p_sigla))
         and (@p_restricao  is null or 
              (@p_restricao is not null and
               (@p_restricao = 'ATIVO' and a.ativo = 'S')
              )
             );
end 