create or replace FUNCTION SP_PutSiwCliente
   (p_operacao             varchar,
    p_chave                numeric,
    p_cliente              numeric,
    p_nome                 varchar,
    p_nome_resumido        varchar,
    p_inicio_atividade     date,
    p_cnpj                 varchar,
    p_sede                 varchar,
    p_inscricao_estadual   varchar,
    p_cidade              numeric,
    p_minimo_senha        numeric,
    p_maximo_senha        numeric,
    p_dias_vigencia       numeric,
    p_aviso_expiracao     numeric,
    p_maximo_tentativas   numeric,
    p_agencia_padrao      numeric,
    p_segmento            numeric,
    p_mail_tramite        varchar,
    p_mail_alerta         varchar,
    p_georeferencia       varchar,
    p_googlemaps_key      varchar,
    p_arp                 varchar
   ) RETURNS VOID AS $$
DECLARE
   w_existe numeric(18);
   w_chave  numeric(18) := p_chave;
   w_chave1 numeric(18);
   w_chave2 numeric(18);
   w_chave3 numeric(18);
BEGIN
   If InStr('IA',p_operacao) > 0 Then
      -- Verifica se a pessoa já existe e decide se é inclusão ou alteração
      select count(*) into w_existe from co_pessoa where sq_pessoa = nvl(p_chave,0);
    
      -- Se não existir, executa a inclusão
      If w_existe = 0 Then
         -- Recupera a próxima chave
         select sq_pessoa.nextval into w_Chave;
          
         -- Insere registro em CO_PESSOA
         insert into co_pessoa (
            sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,
            sq_tipo_pessoa, nome,          nome_resumido)
         (select
            w_Chave,          p_cliente,     b.sq_tipo_vinculo,
            a.sq_tipo_pessoa, p_nome,        p_nome_resumido
            from co_tipo_pessoa  a,
                 co_tipo_vinculo b
           where a.ativo         = 'S'
             and b.ativo         = 'S'
             and upper(a.nome) = 'JURÍDICA'
             and upper(b.nome) = 'SIW'
         );
      Else
         -- Atualiza tabela corporativa de pessoas
         Update co_pessoa set
             nome             = trim(p_nome), 
             nome_resumido    = trim(p_nome_resumido)
         where sq_pessoa      = p_chave;
      End If;
         
      -- Verifica se a pessoa jurídica já existe e decide se é inclusão ou alteração
      select count(*) into w_existe from co_pessoa_juridica where sq_pessoa = nvl(p_chave,0);
    
      -- Se não existir, executa a inclusão
      If w_existe = 0 Then
         -- Grava registro na tabela corporativa de pessoas jurídicas
        Insert into co_pessoa_juridica 
           ( sq_pessoa,      cliente,       inicio_atividade, 
             cnpj,           sede,          inscricao_estadual 
           ) 
        values  
           ( w_Chave,        p_cliente,     p_inicio_atividade,
             p_cnpj,         p_sede,        p_inscricao_estadual
           );
      Else
         -- Atualiza registro na tabela corporativa de pessoas jurídicas
         Update co_pessoa_juridica set
             inicio_atividade   = p_inicio_atividade,
             cnpj               = p_cnpj,
             sede               = p_sede,
             inscricao_estadual = p_inscricao_estadual
         where sq_pessoa      = p_chave;
      End If;
         
      -- Verifica se a pessoa já está vinculada a um segmento
      select count(*) into w_existe from co_pessoa_segmento where sq_pessoa = nvl(p_chave,0);
    
      -- Se não existir, executa a inclusão
      If w_existe = 0 Then
           
        -- Grava registro de identificação do segmento do cliente
        Insert into co_pessoa_segmento ( sq_pessoa, sq_segmento )
         Values (w_chave, p_segmento);
      Else
         -- Atualiza segmento do cliente
         update co_pessoa_segmento set
             sq_segmento     = p_segmento
         where sq_pessoa      = p_chave;
      End If;
         
      -- Verifica se o endereço padrão já existe e decide se é inclusão ou alteração
      select count(*) into w_existe from co_pessoa_juridica where sq_pessoa = nvl(p_chave,0);
    
      -- Se não existir, executa a inclusão
      If w_existe = 0 Then
          
        -- Gera um endereço fictício para o cliente alterar depois
        Insert into co_pessoa_endereco
           ( sq_pessoa_endereco, sq_pessoa,     sq_tipo_endereco,
             logradouro,         sq_cidade,     padrao
           )
        (select sq_pessoa_endereco.nextval, w_chave, a.sq_tipo_endereco,
                'Endereço principal (corrigir)', p_cidade, 'S'
           from co_tipo_endereco a,
               co_tipo_pessoa   b
          where a.sq_tipo_pessoa = b.sq_tipo_pessoa
            and a.ativo         = 'S'
            and b.ativo         = 'S'
            and upper(b.nome)    = 'JURÍDICA'
            and upper(a.nome)    = 'COMERCIAL'
        );
      End If;
         
      -- Verifica se a pessoa já é cliente
      select count(*) into w_existe from siw_cliente where sq_pessoa = nvl(p_chave,0);
    
      -- Se não existir, executa a inclusão
      If w_existe = 0 Then
          
        -- Grava registro na tabela de clientes do SIW
        Insert into siw_cliente 
           ( sq_pessoa,           sq_cidade_padrao,     ativacao, 
             tipo_autenticacao,   tamanho_min_senha,    tamanho_max_senha, 
             dias_vig_senha,      dias_aviso_expir,     maximo_tentativas, 
             sq_agencia_padrao,   envia_mail_tramite,   envia_mail_alerta,
             georeferencia,       googlemaps_key,       ata_registro_preco
           ) 
        values  
           ( w_chave,             p_cidade,             now(),
             1,                   p_minimo_senha,       p_maximo_senha,
             p_dias_vigencia,     p_aviso_expiracao,    p_maximo_tentativas,
             p_agencia_padrao,    p_mail_tramite,       p_mail_alerta,
             p_georeferencia,     p_googlemaps_key,     p_arp
           );
           
        -- Grava tipos de vínculo do cliente a partir do padrão definido para o segmento onde atua
        Insert into co_tipo_vinculo 
           ( sq_tipo_vinculo, sq_tipo_pessoa, cliente, nome, interno, ativo, padrao, contratado, ordem ) 
        (select sq_tipo_vinculo.nextval, 
                a.sq_tipo_pessoa, 
                w_chave, 
                a.nome, a.interno, a.ativo, a.padrao, a.contratado, a.ordem
           from dm_seg_vinculo a 
          where sq_segmento = p_segmento
        );
        
        -- Concede os módulos de opções gerais, controle e estrutura organizacional ao novo cliente
        insert into siw_cliente_modulo (sq_pessoa, sq_modulo)
        (select w_Chave, sq_modulo
           from siw_modulo
          where upper(nome) in ('CONTROLE', 'OPÇÕES GERAIS', 'ESTRUTURA ORGANIZACIONAL')
        );
        
        -- Gera as opções gerais do menu
        PERFORM SG_GeraMenu(w_Chave);
        
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
            and a.sq_pessoa        = w_Chave
        );
        
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
            and a.sq_pessoa        = w_Chave
        );

        -- Grava uma unidade para o superusuário do sistema
        select sq_unidade.nextval into w_chave1;
        
        Insert into eo_unidade ( sq_unidade, sq_pessoa, nome, sigla, ordem)
         Values (w_chave1, w_chave, 'Suporte técnico', 'SUTEC', 99);
         
        -- Grava uma localização para o superusuário do sistema
        select sq_localizacao.nextval into w_chave2;
        
        Insert into eo_localizacao ( sq_localizacao, cliente, sq_unidade, nome)
         Values (w_chave2, w_chave, w_chave1, 'Sala virtual');
         
        -- Grava um superusuário para o cliente
        select sq_pessoa.nextval into w_Chave3;
        
        Insert into co_pessoa
          ( sq_pessoa, sq_pessoa_pai, sq_tipo_pessoa,   nome,           nome_resumido )
        (select w_chave3, w_chave,    a.sq_tipo_pessoa, 'SBPI Suporte', 'SBPI Suporte'
           from co_tipo_pessoa  a
          where a.ativo       = 'S'
            and upper(a.nome) = 'FÍSICA'
        );
        
        Insert into sg_autenticacao 
           ( sq_pessoa,   username,       senha,     assinatura, 
             sq_unidade,  sq_localizacao, cliente,   email, 
             gestor_seguranca, gestor_sistema) 
        Values (w_chave3, '000.000.001-91', criptografia(acentos('xyz345aix')), criptografia(acentos('xyz345aix')),
                w_chave1, w_chave2,        w_chave,  'desenv@sbpi.com.br',
                'S',      'S'
               );

        -- Insere registros de configuração de e-mail
        insert into sg_pessoa_mail(sq_pessoa_mail, sq_pessoa, sq_menu, alerta_diario, tramitacao, conclusao, responsabilidade)
        (select sq_pessoa_mail.nextval, a.sq_pessoa, c.sq_menu, 'S', 'S', 'S', 
                case when substr(c.sigla, 1,2) = 'PJ' then 'S' else 'N' end
           from sg_autenticacao        a 
                inner   join co_pessoa b on (a.sq_pessoa     = b.sq_pessoa)
                  inner join siw_menu  c on (b.sq_pessoa_pai = c.sq_pessoa and c.tramite = 'S')
          where 0   = (select count(*) from sg_pessoa_mail where sq_pessoa = a.sq_pessoa and sq_menu = c.sq_menu)
            and a.sq_pessoa = w_chave3
        );
      Else
        -- Atualiza registro na tabela de clientes do SIW
        update siw_cliente set
            sq_agencia_padrao    = p_agencia_padrao,
            sq_cidade_padrao     = p_cidade,
            tamanho_min_senha    = p_minimo_senha,
            tamanho_max_senha    = p_maximo_senha,
            dias_vig_senha       = p_dias_vigencia,
            dias_aviso_expir     = p_aviso_expiracao,
            maximo_tentativas    = p_maximo_tentativas,
            envia_mail_tramite   = p_mail_tramite,
            envia_mail_alerta    = p_mail_alerta,
            georeferencia        = p_georeferencia,
            googlemaps_key       = p_googlemaps_key,
            ata_registro_preco   = p_arp
        where sq_pessoa      = p_chave;
      End If;
   Elsif p_Operacao = 'E' Then
      -- Remove o registro na tabela de configuração de e-mail
      DELETE FROM sg_pessoa_mail where sq_pessoa = (select sq_pessoa from sg_autenticacao x where username='000.000.001-91' and sq_pessoa = (select sq_pessoa from co_pessoa where sq_pessoa = x.sq_pessoa and sq_pessoa_pai = p_chave));

      -- Remove o usuário de suporte técnico
      DELETE FROM sg_autenticacao x where username='000.000.001-91' and sq_pessoa = (select sq_pessoa from co_pessoa where sq_pessoa = x.sq_pessoa and sq_pessoa_pai = p_chave);
      
      -- Remove a pessoa cadastrada para ser suporte técnico
      DELETE FROM co_pessoa where nome='SBPI Suporte' and sq_pessoa_pai = p_chave;
      
      -- Remove a localização virtual
      DELETE FROM eo_localizacao a where a.sq_unidade = (select sq_unidade from eo_unidade where sq_pessoa = p_Chave);
      
      -- Remove a unidade virtual
      DELETE FROM eo_unidade a where a.sq_pessoa = p_Chave;
      
      -- Remove as permissões de menu a perfil
      DELETE FROM sg_perfil_menu a where a.sq_pessoa_endereco = (select sq_pessoa_endereco from co_pessoa_endereco where sq_pessoa = p_Chave);
      
      -- Remove as permissões de menu a endereço
      DELETE FROM siw_menu_endereco a where a.sq_pessoa_endereco = (select sq_pessoa_endereco from co_pessoa_endereco where sq_pessoa = p_Chave);
      
      -- Remove as opções do menu
      DELETE FROM siw_menu a where a.sq_pessoa = p_Chave;
      
      -- Remove os módulos do cliente
      DELETE FROM siw_cliente_modulo a where a.sq_pessoa = p_Chave;
      
      -- Remove os tipos de vínculo
      DELETE FROM co_tipo_vinculo a where a.cliente = p_Chave;
      
      -- Remove da tabela de clientes do SIW
      DELETE FROM siw_cliente a where a.sq_pessoa = p_Chave;
      
      -- Remove o endereço virtual
      DELETE FROM co_pessoa_endereco where sq_pessoa = p_Chave;
      
      -- Remove o cliente da tabela de segmentos
      DELETE FROM co_pessoa_segmento a where a.sq_pessoa = p_Chave;
      
      -- Remove da tabela corporativa de pessoas jurídicas
      DELETE FROM co_pessoa_juridica a where a.sq_pessoa = p_Chave;
      
      -- Remove da tabela corporativa de pessoas
      DELETE FROM co_pessoa a where a.sq_pessoa = p_Chave;
      
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;