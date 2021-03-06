SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutEOUnidade
   (@p_operacao                 varchar(1),
    @p_chave                    int         = null,
    @p_sq_tipo_unidade          int         = null,
    @p_sq_area_atuacao          int         = null,
    @p_sq_unidade_gestora       int         = null,
    @p_sq_unidade_pai           int         = null,
    @p_sq_unidade_pagadora      int         = null,
    @p_sq_pessoa_endereco       int         = null,
    @p_ordem                    int         = null,
    @p_email                    varchar(60) = null,
    @p_codigo                   varchar(15) = null,
    @p_cliente                  int         = null,
    @p_nome                     varchar(50) = null,
    @p_sigla                    varchar(20) = null,
    @p_informal                 varchar(1)  = null,
    @p_vinculada                varchar(1)  = null,
    @p_adm_central              varchar(1)  = null,
    @p_unidade_gestora          varchar(1)  = null,
    @p_unidade_pagadora         varchar(1)  = null,
    @p_ativo                    varchar(1)  = null 
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
     insert into eo_unidade (sq_tipo_unidade,
                 sq_area_atuacao,sq_unidade_gestora,sq_unidade_pai,
                 sq_unid_pagadora,sq_pessoa_endereco,ordem,email,
                 codigo,sq_pessoa, nome,sigla,informal,vinculada,adm_central,
                  unidade_gestora,unidade_pagadora,ativo)
      values (
                 @p_sq_tipo_unidade,
                 @p_sq_area_atuacao,
                 @p_sq_unidade_gestora,
                 @p_sq_unidade_pai,
                 @p_sq_unidade_pagadora,
                 @p_sq_pessoa_endereco,
                 @p_ordem,
                 @p_email,
                 @p_codigo,
                 @p_cliente,
                 rtrim(@p_nome),
                 rtrim(@p_sigla),
                 @p_informal,
                 @p_vinculada,
                 @p_adm_central,
                 @p_unidade_gestora,
                 @p_unidade_pagadora,
                 @p_ativo
         )
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update eo_unidade set
         sq_tipo_unidade      = @p_sq_tipo_unidade,
         sq_area_atuacao      = @p_sq_area_atuacao,
         sq_unidade_gestora   = @p_sq_unidade_gestora,
         sq_unidade_pai       = @p_sq_unidade_pai,
         sq_unid_pagadora     = @p_sq_unidade_pagadora,
         sq_pessoa_endereco   = @p_sq_pessoa_endereco,
         ordem                = @p_ordem,
         email                = @p_email,
         codigo               = @p_codigo,
         nome                 = rtrim(@p_nome),
         sigla                = rtrim(@p_sigla),
         informal             = @p_informal,
         vinculada            = @p_vinculada,
         adm_central          = @p_adm_central,
         unidade_gestora      = @p_unidade_gestora,
         unidade_pagadora     = @p_unidade_pagadora,
         ativo                = @p_ativo
      where sq_unidade   = @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete eo_unidade where sq_unidade = @p_chave
   End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

