SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO



create procedure dbo.SP_PutDemandaConc
   (@p_menu               int,
    @p_chave              int            = null,
    @p_pessoa             int            = null,
    @p_tramite            int            = null,
    @p_inicio_real        datetime       = null,
    @p_fim_real           datetime       = null,
    @p_nota_conclusao     varchar(2000)  = null,
    @p_custo_real         numeric(18,2)  = null
   ) as
begin
   -- Insere registro na tabela de log da solicitacao
   Insert Into siw_solic_log 
      (sq_siw_solicitacao,        sq_pessoa, 
       sq_siw_tramite,            data,
       devolucao,                 observacao
      )
   Values
      (@p_chave,                  @p_pessoa,
       @p_tramite,                getdate(),
       'N',                       'Conclusão da demanda')
       
   -- Atualiza o registro da demanda com os dados da conclusão.
   Update gd_demanda set
      inicio_real     = @p_inicio_real,
      fim_real        = @p_fim_real,
      nota_conclusao  = @p_nota_conclusao,
      custo_real      = @p_custo_real,
      concluida       = 'S',
      data_conclusao  = getdate()
   Where sq_siw_solicitacao = @p_chave

   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu=@p_menu 
                           and IsNull(sigla,'z')='AT'
                       )
   Where sq_siw_solicitacao = @p_chave
end 






GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

