alter procedure dbo.SP_PutCTCC
   (@operacao  varchar(1),
    @chave     int          = null,
    @sq_cc_pai int          = null,
    @cliente   int          = null,
    @nome      varchar(60)  = null,
    @descricao varchar(500) = null,
    @sigla     varchar(20)  = null,
    @receita   varchar(1)   = null,
    @regular   varchar(1)   = null,
    @ativo     varchar(1)   = null
   ) as
begin
   If @operacao = 'I'
      -- Insere registro
      insert into ct_cc (sq_cc_pai, cliente, nome, descricao, sigla, receita, regular, ativo)
      values (
                 @sq_cc_pai,
                 @cliente,
                 rtrim(ltrim(@nome)),
                 rtrim(ltrim(@descricao)),
                 rtrim(ltrim(@sigla)),
                 @receita,
                 @regular,
                 @ativo
         );
   Else If @operacao = 'A'
      -- Altera registro
      update ct_cc set
         sq_cc_pai = @sq_cc_pai,
         nome      = rtrim(ltrim(@nome)),
         descricao = rtrim(ltrim(@descricao)),
         sigla     = rtrim(ltrim(@sigla)),
         receita   = @receita,
         regular   = @regular
      where sq_cc = @chave;
   Else If @operacao = 'E'
      -- Exclui registro
      delete ct_cc where sq_cc = @chave;
   Else If @operacao = 'T'
      -- Ativa registro
      update ct_cc set ativo = 'S' where sq_cc = @chave;
   Else If @operacao = 'D'
      -- Desativa registro
      update ct_cc set ativo = 'N' where sq_cc = @chave;
end