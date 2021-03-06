alter procedure dbo.SP_PutCOTipoVinc
   (@operacao                 varchar(1),
    @chave                    int         = null,
    @sq_tipo_pessoa           int         = null,
    @cliente                  int         = null,
    @nome                     varchar(20) = null,
    @interno                  varchar(1)  = null,
    @contratado               varchar(1)  = null,
    @padrao                   varchar(1)  = null,
    @ativo                    varchar(1)  = null,
    @mail_tramite             varchar(1)  = null,
    @mail_alerta              varchar(1)  = null
   ) as
begin
    If @operacao = 'I' begin
      -- Insere registro
      insert into co_tipo_vinculo ( sq_tipo_pessoa, cliente, nome, interno, contratado, padrao, ativo, envia_mail_tramite, envia_mail_alerta)
         (select @sq_tipo_pessoa,
                 @cliente,
                 rtrim(ltrim(@nome)),
                 @interno,
                 @contratado,
                 @padrao,
                 @ativo,
                 @mail_tramite,
                 @mail_alerta
            
         );
   end Else if @operacao = 'A' begin
      -- Altera registro
      update co_tipo_vinculo set
         sq_tipo_pessoa     = @sq_tipo_pessoa,
         nome               = rtrim(ltrim(@nome)),
         interno            = @interno,
         contratado         = @contratado,
         padrao             = @padrao,
         ativo              = @ativo,
         envia_mail_tramite = @mail_tramite,
         envia_mail_alerta  = @mail_alerta
      where sq_tipo_vinculo = @chave;
 end Else if @operacao = 'E' begin
      -- Exclui registro
      delete co_tipo_vinculo where sq_tipo_vinculo = @chave;
   End 
end