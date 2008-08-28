alter  procedure Sp_PutSiwPessoaMail
   (@operacao          varchar(1),
    @sq_pessoa            int,
    @sq_menu              int        = null,
    @alerta            varchar(1) = null,
    @tramitacao        varchar(1) = null,
    @conclusao         varchar(1) = null,
    @responsabilidade  varchar(1) = null
   ) as
begin
   If @operacao = 'I' begin
      -- Insere registro em SG_PESSOA_MAIL, para cada servi�o que cont�nha a op��o
      insert into sg_pessoa_mail (  sq_pessoa, sq_menu, alerta_diario, tramitacao,
                                  conclusao,              responsabilidade) 
                          values ( @sq_pessoa,  @sq_menu, @alerta,       @tramitacao,
                                  @conclusao,            @responsabilidade);
   end else if @operacao = 'E' begin
      -- Remove a permiss�o
       delete sg_pessoa_mail
        where sq_pessoa = @sq_pessoa;
   End
end ;