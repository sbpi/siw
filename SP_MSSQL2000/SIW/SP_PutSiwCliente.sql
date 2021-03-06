SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutSiwCliente
   (@p_operacao           varchar(1),
    @p_chave              int         = null,
    @p_cliente            int         = null,
    @p_nome               varchar(60) = null,
    @p_nome_resumido      varchar(15) = null,
    @p_inicio_atividade   datetime    = null,
    @p_cnpj               varchar(20) = null,
    @p_sede               varchar(1)  = null,
    @p_inscricao_estadual varchar(20) = null,
    @p_cidade             int         = null,
    @p_minimo_senha       int         = null,
    @p_maximo_senha       int         = null,
    @p_dias_vigencia      int         = null,
    @p_aviso_expiracao    int         = null,
    @p_maximo_tentativas  int         = null,
    @p_agencia_padrao     int         = null,
    @p_segmento           int         = null
   ) as
begin
   Declare @w_existe int
   Declare @w_chave  int
   Declare @w_chave1 int
   Declare @w_chave2 int
   Declare @w_chave3 int

   If CharIndex(@p_operacao, 'IA') > 0 Begin
      -- Verifica se a pessoa já existe e decide se é inclusão ou alteração
      select @w_existe = count(*) from co_pessoa where sq_pessoa = IsNull(@p_chave,0)
    
      -- Se não existir, executa a inclusão
      If @w_existe = 0 Begin
         -- Insere registro em CO_PESSOA
         insert into co_pessoa (
            sq_pessoa_pai,    sq_tipo_vinculo,
            sq_tipo_pessoa,   nome,              nome_resumido,
            nome_indice,      nome_resumido_ind)
         (select
            @p_cliente,       b.sq_tipo_vinculo,
            a.sq_tipo_pessoa, @p_nome,           @p_nome_resumido,
            siw.acentos(@p_nome, null), siw.acentos(@p_nome_resumido, null) 
            from co_tipo_pessoa  a,
                 co_tipo_vinculo b
           where a.ativo         = 'S'
             and b.ativo         = 'S'
             and upper(a.nome) = 'JURÍDICA'
             and upper(b.nome) = 'SIW'
         )
         
         -- Recupera a próxima chave
         Set @w_Chave = @@IDENTITY
          
         -- Grava registro na tabela corporativa de pessoas jurídicas
        Insert into co_pessoa_juridica 
           ( sq_pessoa,      cliente,       inicio_atividade, 
             cnpj,           sede,          inscricao_estadual 
           ) 
        values  
           ( @w_Chave,        @p_cliente,     @p_inicio_atividade,
             @p_cnpj,         @p_sede,        @p_inscricao_estadual
           )
           
        -- Grava registro de identificação do segmento do cliente
        Insert into co_pessoa_segmento ( sq_pessoa, sq_segmento )
         Values (@w_chave, @p_segmento)
          
        -- Gera um endereço fictício para o cliente alterar depois
        Insert into co_pessoa_endereco
           ( sq_pessoa,     sq_tipo_endereco,   logradouro,
             sq_cidade,     padrao
           )
        (select 
             @w_chave,       a.sq_tipo_endereco, 'Endereço principal (corrigir)', 
             @p_cidade,     'S'
           from co_tipo_endereco a,
               co_tipo_pessoa   b
          where a.sq_tipo_pessoa = b.sq_tipo_pessoa
            and a.ativo         = 'S'
            and b.ativo         = 'S'
            and upper(b.nome)    = 'JURÍDICA'
            and upper(a.nome)    = 'COMERCIAL'
        )
        
        -- Grava registro na tabela de clientes do SIW
        Insert into siw_cliente 
           ( sq_pessoa,           sq_cidade_padrao,     ativacao, 
             tipo_autenticacao,   tamanho_min_senha,    tamanho_max_senha, 
             dias_vig_senha,      dias_aviso_expir,     maximo_tentativas, 
             sq_agencia_padrao 
           ) 
        values  
           ( @w_chave,            @p_cidade,             getdate(),
             1,                   @p_minimo_senha,       @p_maximo_senha,
             @p_dias_vigencia,    @p_aviso_expiracao,    @p_maximo_tentativas,
             @p_agencia_padrao
           )
           
        -- Grava tipos de vínculo do cliente a partir do padrão definido para o segmento onde atua
        Insert into co_tipo_vinculo 
           ( sq_tipo_pessoa, cliente, nome, interno, ativo, padrao, contratado, ordem ) 
        (select a.sq_tipo_pessoa, 
                @w_chave, 
                a.nome, a.interno, a.ativo, a.padrao, a.contratado, a.ordem
           from dm_seg_vinculo a 
          where sq_segmento = @p_segmento
        )
        
        -- Concede os módulos de opções gerais, controle e estrutura organizacional ao novo cliente
        insert into siw_cliente_modulo (sq_pessoa, sq_modulo)
        (select @w_Chave, sq_modulo
           from siw_modulo
          where upper(nome) in ('CONTROLE', 'OPÇÕES GERAIS', 'ESTRUTURA ORGANIZACIONAL')
        )
        
        -- Gera as opções gerais do menu
        exec SG_GeraMenu @w_Chave
        
        -- Habilita as opções do menu para o endereço criado acima, permitindo sua associação
        -- a tipos de vínculo
        Insert into siw_menu_endereco (sq_menu, sq_pessoa_endereco)
        (select a.sq_menu, b.sq_pessoa_endereco
           from siw_menu           a,
                co_pessoa_endereco b,
                co_tipo_endereco   d
          where a.sq_pessoa        = b.sq_pessoa
            and b.sq_tipo_endereco = d.sq_tipo_endereco
            and d.internet         = 'N'
            and d.email            = 'N'
            and a.sq_pessoa        = @w_Chave
        )
        
        -- Habilita as opções gerais do menu para todos os perfis criados
        Insert into sg_perfil_menu (sq_tipo_vinculo, sq_menu, sq_pessoa_endereco)
        (select c.sq_tipo_vinculo, a.sq_menu, b.sq_pessoa_endereco
           from siw_menu           a,
                co_pessoa_endereco b,
                co_tipo_vinculo    c,
                co_tipo_endereco   d,
                siw_modulo         e
          where a.sq_pessoa        = b.sq_pessoa
            and a.sq_pessoa        = c.cliente
            and b.sq_tipo_endereco = d.sq_tipo_endereco
            and a.sq_modulo        = e.sq_modulo
            and c.interno          = 'S'
            and d.internet         = 'N'
            and d.email            = 'N'
            and upper(e.nome)      = 'OPÇÕES GERAIS'
            and a.sq_pessoa        = @w_Chave
        )

        Insert into eo_unidade ( sq_pessoa, nome, sigla, ordem)
         Values (@w_chave, 'Suporte técnico', 'SUTEC', 99)
         
        -- Grava uma unidade para o superusuário do sistema
        Set @w_chave1 = @@IDENTITY
        
        Insert into eo_localizacao ( sq_unidade, nome)
         Values (@w_chave1, 'Sala virtual')
         
        -- Grava uma localização para o superusuário do sistema
        Set @w_chave2 = @@IDENTITY
        
        Insert into co_pessoa
          ( sq_pessoa_pai, sq_tipo_pessoa,   nome,           nome_resumido )
        (select @w_chave,    a.sq_tipo_pessoa, 'SBPI Suporte', 'SBPI Suporte'
           from co_tipo_pessoa  a
          where a.ativo       = 'S'
            and upper(a.nome) = 'FÍSICA'
        )
        
        -- Grava um superusuário para o cliente
        Set @w_Chave3 = @@IDENTITY
        
        Insert into sg_autenticacao 
           ( sq_pessoa,   username,       senha,     assinatura, 
             sq_unidade,  sq_localizacao, cliente,   email, 
             gestor_seguranca, gestor_sistema) 
        Values (@w_chave3, '000.000.001-91', siw.criptografia('000.000.001-91'), siw.criptografia('000.000.001-91'),
                @w_chave1, @w_chave2,        @w_chave,  lower(@p_nome_resumido)+'@sbpi.com.br',
                'S',      'S'
               )


      -- Se existir, executa a alteração
      End Else Begin
         -- Atualiza tabela corporativa de pessoas
         Update co_pessoa set
             nome             = rtrim(@p_nome), 
             nome_resumido    = rtrim(@p_nome_resumido),
             nome_indice      = siw.acentos(rtrim(@p_nome),null), 
             nome_resumido_ind= siw.acentos(rtrim(@p_nome_resumido),null)
         where sq_pessoa      = @p_chave
         
         -- Atualiza registro na tabela corporativa de pessoas jurídicas
         Update co_pessoa_juridica set
             inicio_atividade   = @p_inicio_atividade,
             cnpj               = @p_cnpj,
             sede               = @p_sede,
             inscricao_estadual = @p_inscricao_estadual
         where sq_pessoa      = @p_chave

         -- Atualiza registro na tabela de clientes do SIW
         update siw_cliente set
             sq_agencia_padrao    = @p_agencia_padrao,
             sq_cidade_padrao     = @p_cidade,
             tamanho_min_senha    = @p_minimo_senha,
             tamanho_max_senha    = @p_maximo_senha,
             dias_vig_senha       = @p_dias_vigencia,
             dias_aviso_expir     = @p_aviso_expiracao,
             maximo_tentativas    = @p_maximo_tentativas
         where sq_pessoa          = @p_chave
         
         -- Atualiza segmento do cliente
         update co_pessoa_segmento set
             sq_segmento     = @p_segmento
         where sq_pessoa     = @p_chave
       End
   End Else If @p_Operacao = 'E' Begin
      -- Remove o usuário de suporte técnico
      delete sg_autenticacao where username='000.000.001-91' and sq_pessoa = (select sq_pessoa from co_pessoa where nome='SBPI Suporte' and sq_pessoa_pai = @p_chave)
      
      -- Remove a pessoa cadastrada para ser suporte técnico
      delete co_pessoa where nome='SBPI Suporte' and sq_pessoa_pai = @p_chave
      
      -- Remove a localização virtual
      delete eo_localizacao where sq_unidade = (select sq_unidade from eo_unidade where sq_pessoa = @p_Chave)
      
      -- Remove a unidade virtual
      delete eo_unidade where sq_pessoa = @p_Chave
      
      -- Remove as permissões de menu a perfil
      delete sg_perfil_menu where sq_pessoa_endereco = (select sq_pessoa_endereco from co_pessoa_endereco where sq_pessoa = @p_Chave)
      
      -- Remove as permissões de menu a endereço
      delete siw_menu_endereco where sq_pessoa_endereco = (select sq_pessoa_endereco from co_pessoa_endereco where sq_pessoa = @p_Chave)
      
      -- Remove as opções do menu
      delete siw_menu where sq_pessoa = @p_Chave
      
      -- Remove os módulos do cliente
      delete siw_cliente_modulo where sq_pessoa = @p_Chave
      
      -- Remove os tipos de vínculo
      delete co_tipo_vinculo where cliente = @p_Chave
      
      -- Remove da tabela de clientes do SIW
      delete siw_cliente where sq_pessoa = @p_Chave
      
      -- Remove o endereço virtual
      delete co_pessoa_endereco where sq_pessoa = @p_Chave
      
      -- Remove o cliente da tabela de segmentos
      delete co_pessoa_segmento where sq_pessoa = @p_Chave
      
      -- Remove da tabela corporativa de pessoas jurídicas
      delete co_pessoa_juridica where sq_pessoa = @p_Chave
      
      -- Remove da tabela corporativa de pessoas
      delete co_pessoa where sq_pessoa = @p_Chave
      
   End
end


GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

