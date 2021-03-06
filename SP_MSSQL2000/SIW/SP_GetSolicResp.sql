SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO

create procedure dbo.SP_GetSolicResp
   (@p_chave        int,
    @p_fase         varchar(200) = null,
    @p_restricao    varchar(50)  = null) as
    
begin
   Declare @l_item       varchar(18)
   Declare @l_fase       varchar(200)
   Declare @x_fase       varchar(200)
   
   Set @l_fase = @p_fase + ','
   Set @x_fase = ''

   If @p_fase is not null Begin
      While len(IsNull(@l_fase,'')) > 0 Begin
         Set @l_item  = lTrim(rTrim(substring(@l_fase,1,CharIndex(',',@l_fase)-1)))
         If Len(IsNull(@l_item,'')) > 0 Set @x_fase = @x_fase + ',''' + @l_item + ''''
         Set @l_fase = substring(@l_fase,CharIndex(',',@l_fase)+1,200)
      End
      Set @x_fase = substring(@x_fase,2,200)
   End

   If @p_restricao = 'GENERICO' Begin
      -- Recupera as demandas que o usuário pode ver
         select distinct d.sq_pessoa, d.nome, d.nome_resumido,
                e.email, e.ativo ativo_usuario,
                f.sigla sg_unidade
           from siw_solicitacao                       a
                inner       join siw_solic_log        b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                  inner     join siw_tramite          c on (b.sq_siw_tramite     = c.sq_siw_tramite)
                  inner     join co_pessoa            d on (b.sq_pessoa          = d.sq_pessoa)
                    inner   join sg_autenticacao      e on (d.sq_pessoa          = e.sq_pessoa)
                      inner join eo_unidade           f on (e.sq_unidade         = f.sq_unidade)
          where b.sq_siw_solicitacao = @p_chave
            and e.ativo              = 'S'
            and (@p_fase              is null or (@p_fase        is not null and CharIndex(''''+cast(b.sq_siw_tramite as varchar)+'''',@x_fase) > 0))
         UNION
         select distinct b.sq_pessoa, b.nome, b.nome_resumido,
                c.email, c.ativo ativo_usuario,
                d.sigla sg_unidade
           from siw_solicitacao                     a
                inner     join co_pessoa            b on (a.solicitante        = b.sq_pessoa)
                  inner   join sg_autenticacao      c on (b.sq_pessoa          = c.sq_pessoa)
                    inner join eo_unidade           d on (c.sq_unidade         = d.sq_unidade)
          where a.sq_siw_solicitacao = @p_chave
            and c.ativo              = 'S'
         UNION
         select distinct c.sq_pessoa, c.nome, c.nome_resumido,
                d.email, d.ativo ativo_usuario,
                e.sigla sg_unidade
           from siw_solicitacao                       a
                inner       join eo_unidade_resp      b on (a.sq_unidade         = b.sq_unidade)
                inner       join co_pessoa            c on (b.sq_pessoa          = c.sq_pessoa)
                  inner     join sg_autenticacao      d on (c.sq_pessoa          = d.sq_pessoa)
                      inner join eo_unidade           e on (d.sq_unidade         = e.sq_unidade)
          where a.sq_siw_solicitacao = @p_chave
            and b.tipo_respons       = 'T'
            and b.fim                is null
            and d.ativo              = 'S'
         UNION
         select distinct c.sq_pessoa, c.nome, c.nome_resumido,
                d.email, d.ativo ativo_usuario,
                e.sigla sg_unidade
           from siw_solicitacao                       a
                inner       join eo_unidade_resp      b on (a.sq_unidade         = b.sq_unidade)
                inner       join co_pessoa            c on (b.sq_pessoa          = c.sq_pessoa)
                  inner     join sg_autenticacao      d on (c.sq_pessoa          = d.sq_pessoa)
                      inner join eo_unidade           e on (d.sq_unidade         = e.sq_unidade)
          where a.sq_siw_solicitacao = @p_chave
            and b.tipo_respons       = 'S'
            and d.ativo              = 'S'
            and b.fim                is null
   End Else If @p_restricao = 'CADASTRAMENTO' Begin
      -- Recupera as demandas que o usuário pode ver
         select distinct d.sq_pessoa, d.nome, d.nome_resumido,
                e.email, e.ativo ativo_usuario,
                f.sigla sg_unidade
           from siw_solicitacao                       a
                inner       join siw_solic_log        b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                  inner     join siw_tramite          c on (b.sq_siw_tramite     = c.sq_siw_tramite)
                  inner     join co_pessoa            d on (b.sq_pessoa          = d.sq_pessoa)
                    inner   join sg_autenticacao      e on (d.sq_pessoa          = e.sq_pessoa)
                      inner join eo_unidade           f on (e.sq_unidade         = f.sq_unidade)
          where b.sq_siw_solicitacao = @p_chave
            and c.sigla              = 'CI'
            and e.ativo              = 'S'
         UNION
         select distinct b.sq_pessoa, b.nome, b.nome_resumido,
                c.email, c.ativo ativo_usuario,
                d.sigla sg_unidade
           from siw_solicitacao                     a
                inner     join co_pessoa            b on (a.solicitante        = b.sq_pessoa)
                  inner   join sg_autenticacao      c on (b.sq_pessoa          = c.sq_pessoa)
                    inner join eo_unidade           d on (c.sq_unidade         = d.sq_unidade)
          where a.sq_siw_solicitacao = @p_chave
            and c.ativo              = 'S'
         UNION
         select distinct c.sq_pessoa, c.nome, c.nome_resumido,
                d.email, d.ativo ativo_usuario,
                e.sigla sg_unidade
           from siw_solicitacao                       a
                inner       join eo_unidade_resp      b on (a.sq_unidade         = b.sq_unidade)
                inner       join co_pessoa            c on (b.sq_pessoa          = c.sq_pessoa)
                  inner     join sg_autenticacao      d on (c.sq_pessoa          = d.sq_pessoa)
                      inner join eo_unidade           e on (d.sq_unidade         = e.sq_unidade)
          where a.sq_siw_solicitacao = @p_chave
            and b.tipo_respons       = 'T'
            and b.fim                is null
            and d.ativo              = 'S'
         UNION
         select distinct c.sq_pessoa, c.nome, c.nome_resumido,
                d.email, d.ativo ativo_usuario,
                e.sigla sg_unidade
           from siw_solicitacao                       a
                inner       join eo_unidade_resp      b on (a.sq_unidade         = b.sq_unidade)
                inner       join co_pessoa            c on (b.sq_pessoa          = c.sq_pessoa)
                  inner     join sg_autenticacao      d on (c.sq_pessoa          = d.sq_pessoa)
                      inner join eo_unidade           e on (d.sq_unidade         = e.sq_unidade)
          where a.sq_siw_solicitacao = @p_chave
            and b.tipo_respons       = 'S'
            and d.ativo              = 'S'
            and b.fim                is null
   End
end

GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

