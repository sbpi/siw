SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetAgreeType
   (@p_chave     int         = null,
    @p_chave_aux int         = null,
    @p_cliente   int,
    @p_restricao varchar(50) =  null
   ) as
begin
   If @p_restricao is null Begin
      -- Recupera os tipos de contrato do cliente
         select a.sq_tipo_acordo,     case when b.nome is null then a.nome else b.nome+' - '+a.nome end nm_tipo,
                a.sq_tipo_acordo_pai, a.nome,            a.sigla, a.modalidade, 
                a.prazo_indeterm,     a.pessoa_fisica,   a.pessoa_juridica, a.ativo
           from ac_tipo_acordo a
                   inner join ac_tipo_acordo b on (a.sq_tipo_acordo_pai = b.sq_tipo_acordo)
          where a.ativo              = 'S' 
            and a.cliente            = @p_cliente
         order by 2
   End Else If @p_restricao = 'HERANCA' Begin
      -- Recupera os tipos de contrato do cliente
         select a.sq_tipo_acordo,     case when b.nome is null then a.nome else b.nome+' - '+a.nome end nm_tipo,
                a.sq_tipo_acordo_pai, a.nome,            a.sigla, a.modalidade, 
                a.prazo_indeterm,     a.pessoa_fisica,   a.pessoa_juridica, a.ativo
           from ac_tipo_acordo a
                   left outer join ac_tipo_acordo b on (a.sq_tipo_acordo_pai = b.sq_tipo_acordo)
          where a.ativo              = 'S' 
            and a.cliente            = @p_cliente
         order by 2
   End Else If @p_restricao = 'ALTERA' Begin
      -- Recupera os tipos de contrato do cliente
         select a.sq_tipo_acordo,
                a.sq_tipo_acordo_pai, a.nome,            a.sigla, a.modalidade, 
                a.prazo_indeterm,     a.pessoa_fisica,   a.pessoa_juridica, a.ativo
           from ac_tipo_acordo a
          where a.sq_tipo_acordo = @p_chave
         order by 2
   End Else If @p_restricao = 'SUBORDINACAO' Begin
      -- Recupera os tipos de contrato do cliente para seleção de subordinação
         select a.sq_tipo_acordo, a.nome nm_tipo
           from ac_tipo_acordo a
          where a.ativo              = 'S' 
            and a.cliente            = @p_cliente
            and a.sq_tipo_acordo_pai is null
            and (@p_chave_aux         is null or (@p_chave_aux is not null and a.sq_tipo_acordo <> @p_chave_aux))
         order by 2
   End Else If @p_restricao = 'PAI' Begin
      -- Recupera os tipos de contrato do cliente que não são subordinados a ninguém
         select a.sq_tipo_acordo, a.nome, a.modalidade, a.prazo_indeterm, a.ativo, a.pessoa_juridica, 
                 a.pessoa_fisica, a.sigla, IsNull(b.Filho,0) Filho 
         from ac_tipo_acordo a
                 left outer join (select sq_tipo_acordo_pai,count(*) Filho 
                                    from ac_tipo_acordo x 
                                   where cliente = @p_cliente 
                                  group by sq_tipo_acordo_pai) b 
                               on (a.sq_tipo_acordo = b.sq_tipo_acordo_pai)
         where a.cliente               = @p_cliente
           and a.sq_tipo_acordo_pai    is null
         order by a.nome
   End Else If @p_restricao = 'FILHO' Begin
      -- Recupera os tipos de contrato do cliente, subordinados ao tipo informado
         select a.sq_tipo_acordo, a.nome, a.modalidade, a.prazo_indeterm, a.ativo, a.pessoa_juridica, 
                 a.pessoa_fisica, a.sigla, IsNull(b.Filho,0) Filho 
         from ac_tipo_acordo a
                 left outer join (select sq_tipo_acordo_pai,count(*) Filho 
                                    from ac_tipo_acordo x 
                                   where cliente = @p_cliente 
                                  group by sq_tipo_acordo_pai) b 
                               on (a.sq_tipo_acordo = b.sq_tipo_acordo_pai)
         where a.cliente               = @p_cliente
           and a.sq_tipo_acordo_pai    = @p_chave
         order by a.nome
   End
end


GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

