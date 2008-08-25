alter procedure dbo.sp_getTipoInteressado
   (@p_cliente   int              ,
    @p_servico   int         =null,
    @p_chave     int         =null,
    @p_nome      varchar(60) =null,
    @p_sigla     varchar(15) =null,
    @p_ativo     varchar(1)  =null,
    @p_restricao varchar(15) =null) as
begin
   If @p_restricao = 'REGISTROS' begin
      -- Recupera os tipos de interessado existentes
         select a.sq_tipo_interessado as chave, a.sq_menu, a.nome,
                a.ordem, a.sigla, a.descricao, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                b.nome + ' (' + c.nome + ')' as nm_servico
           from siw_tipo_interessado    a
                inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
                  inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
          where b.sq_pessoa          = @p_cliente
            and (@p_servico           is null or (@p_servico is not null and a.sq_menu = @p_servico))
            and (@p_chave             is null or (@p_chave is not null and a.sq_tipo_interessado = @p_chave))
            and (@p_nome              is null or (@p_nome is not null and a.nome = @p_nome))
            and (@p_sigla             is null or (@p_sigla is not null and a.sigla = upper(@p_sigla)))
            and (@p_ativo             is null or (@p_ativo is not null and a.ativo = @p_ativo))
         order by a.ordem, a.nome;
   end else if @p_restricao = 'EXISTE' begin
      -- Verifica se há outro registro com o mesmo nome ou sigla
         select a.sq_tipo_interessado as chave, a.sq_menu, a.nome,
                a.ordem, a.sigla, a.descricao, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from siw_tipo_interessado    a
                inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
                  inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
          where a.sq_menu                = @p_servico
            and a.sq_tipo_interessado    <> coalesce(@p_chave,0)
            and (@p_nome                  is null or (@p_nome    is not null and  dbo.acentos(a.nome) =  dbo.acentos(@p_nome)))
            and (@p_sigla                 is null or (@p_sigla   is not null and  dbo.acentos(a.sigla) =  dbo.acentos(@p_sigla)))
            and (@p_ativo                 is null or (@p_ativo   is not null and a.ativo = @p_ativo))
         order by a.ordem, a.nome;
   end else if @p_restricao = 'VINCULADO' begin
      -- Verifica se o registro está vinculado a um interessado
         select a.sq_tipo_interessado as chave, a.sq_menu, a.nome,
                a.ordem, a.sigla, a.descricao, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from siw_tipo_interessado                       a
                inner     join siw_solicitacao_interessado b on (a.sq_tipo_interessado = b.sq_tipo_interessado)
                  inner   join siw_solicitacao             c on (b.sq_siw_solicitacao  = c.sq_siw_solicitacao)
                    inner join siw_menu                    d on (c.sq_menu             = d.sq_menu)
          where d.sq_pessoa            = @p_cliente
            and a.sq_menu              = @p_servico
            and a.sq_tipo_interessado  = @p_chave
         order by a.ordem, a.nome;
   End
end