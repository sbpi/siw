alter procedure dbo.Sp_PutSiwUsuario
   (@p_operacao            varchar(1),
    @p_chave               int   =null,
    @p_cliente             int   =null,
    @p_nome                varchar(60) =null,
    @p_nome_resumido       varchar(15) =null,
    @p_cpf                 varchar(14) =null,
    @p_sexo                varchar(1) =null,
    @p_vinculo             int   =null,
    @p_tipo_pessoa         varchar(15) =null,
    @p_unidade             int   =null,
    @p_localizacao         int   =null,
    @p_username            varchar(30) =null,
    @p_email               varchar(60) =null,
    @p_gestor_seguranca    varchar(1) =null,
    @p_gestor_sistema      varchar(1) =null,
    @p_tipo_autenticacao   varchar(1) =null
   )as
Begin

   Declare @w_existe int
   Declare @w_chave  int

   If CharIndex(@p_operacao,'IA') > 0 Begin
      -- Verifica se a pessoa já existe e decide se é inclusão ou alteração
      select @w_existe = count(*) from co_pessoa where sq_pessoa = IsNull(@p_chave,0)
    
      -- Se não existir, executa a inclusão
      If @w_existe = 0 Begin
         -- Insere registro em CO_PESSOA
         insert into co_pessoa (
            sq_pessoa_pai,    sq_tipo_vinculo,  sq_tipo_pessoa, 
            nome,             nome_resumido  ,  nome_indice,
            nome_resumido_ind)
         (select
            @p_cliente,       @p_vinculo,       sq_tipo_pessoa, 
            @p_nome,          @p_nome_resumido, dbo.acentos(@p_nome),
            dbo.acentos(@p_nome_resumido)
            from co_tipo_pessoa
           where ativo         = 'S'
             and nome          = @p_tipo_pessoa
         )
         
         -- Recupera a próxima chave
         Set @w_chave = @@IDENTITY

      -- Se existir, executa a alteração
      End Else Begin
         -- Atualiza tabela corporativa de pessoas
         Update co_pessoa set
             sq_tipo_vinculo   = @p_vinculo,
             nome              = rtrim(@p_nome), 
             nome_resumido     = rtrim(@p_nome_resumido),
             nome_indice       = dbo.acentos(@p_nome),
             nome_resumido_ind = dbo.acentos(@p_nome_resumido)
         where sq_pessoa       = @p_chave
      End

      -- Verifica se o usuário já existe e decide se é inclusão ou alteração
      select @w_existe = count(*) from sg_autenticacao where sq_pessoa = IsNull(@p_chave,0)
    
      -- Se não existir, executa a inclusão
      If @w_existe = 0 Begin
          
         -- Insere registro em SG_AUTENTICACAO
         Insert into sg_autenticacao
            ( sq_pessoa,          sq_unidade,       sq_localizacao,
              cliente,            username,         email,
              gestor_seguranca,   gestor_sistema,   senha,          
              assinatura
            )
         Values
            ( IsNull(@w_Chave,@p_chave), @p_unidade,       @p_localizacao,
              @p_cliente,             @p_username,      @p_email,
              @p_gestor_seguranca,    @p_gestor_sistema,dbo.criptografia(@p_username),
              dbo.criptografia(@p_username)
            )
          
      -- Se existir, executa a alteração
      End Else Begin
         
         -- Atualiza registro na tabela de segurança
         Update sg_autenticacao set
             sq_unidade       = @p_unidade,
             sq_localizacao   = @p_localizacao,
             gestor_seguranca = @p_gestor_seguranca,
             gestor_sistema   = @p_gestor_sistema,
             email            = @p_email
         where sq_pessoa      = @p_chave

      End

   End Else If @p_operacao = 'E' Begin
      -- Remove o registro na tabela de segurança
      delete sg_autenticacao where sq_pessoa = @p_chave
        
         -- Atualiza tabela corporativa de pessoas
      delete co_pessoa where sq_pessoa = @p_chave
   End Else Begin
      If @p_operacao = 'T' Begin
         -- Ativa registro
         update sg_autenticacao set ativo = 'S' where sq_pessoa = @p_chave
      End Else If @p_operacao = 'D' Begin
         -- Desativa registro
         update sg_autenticacao set ativo = 'N' where sq_pessoa = @p_chave
      End
   End
end