create or replace function SP_PutSiwUsuario
   (p_operacao            varchar,
    p_chave               numeric,
    p_cliente             numeric,
    p_nome                varchar,
    p_nome_resumido       varchar,
    p_vinculo             numeric,
    p_tipo_pessoa         varchar,
    p_unidade             numeric,
    p_localizacao         numeric,
    p_username            varchar,
    p_email               varchar,
    p_gestor_seguranca    varchar,
    p_gestor_sistema      varchar,
    p_tipo_autenticacao   varchar
   ) returns void as $$
declare
   w_existe numeric(18);
   w_chave  numeric(18);
begin
   If strpos('IA',p_operacao) > 0 Then
      -- Verifica se a pessoa já existe e decide se é inclusão ou alteração
      select count(*) into w_existe from co_pessoa where sq_pessoa = coalesce(p_chave,0);
      -- Se não existir, executa a inclusão
      If w_existe = 0 Then
         -- Recupera a próxima chave
         select nextval('sq_pessoa') into w_Chave;
          
         -- Insere registro em CO_PESSOA
         insert into co_pessoa (
            sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,
            sq_tipo_pessoa, nome,          nome_resumido)
         (select
            w_Chave,        p_cliente,     p_vinculo,
            sq_tipo_pessoa, p_nome,        p_nome_resumido
            from co_tipo_pessoa
           where ativo         = 'S'
             and nome          = p_tipo_pessoa
         );
         
      -- Se existir, executa a alteração
      Else
         -- Atualiza tabela corporativa de pessoas
         Update co_pessoa set
             sq_tipo_vinculo  = p_vinculo,
             nome             = trim(p_nome), 
             nome_resumido    = trim(p_nome_resumido)
         where sq_pessoa      = p_chave;
       End If;

      -- Verifica se o usuário já existe e decide se é inclusão ou alteração
      select count(*) into w_existe from sg_autenticacao where sq_pessoa = coalesce(p_chave,0);
      -- Se não existir, executa a inclusão
      If w_existe = 0 Then
         -- Insere registro em SG_AUTENTICACAO
         Insert into sg_autenticacao
            ( sq_pessoa,            sq_unidade,       sq_localizacao,
              cliente,              username,         email,
              gestor_seguranca,     gestor_sistema,   senha,          
              assinatura,           tipo_autenticacao
            )
         Values
            ( coalesce(w_Chave,p_chave), p_unidade,        p_localizacao,
              p_cliente,            p_username,       p_email,
              p_gestor_seguranca,   p_gestor_sistema, criptografia(p_username),
              criptografia(p_username), 
              p_tipo_autenticacao
            );
      -- Se existir, executa a alteração
      Else
         -- Atualiza registro na tabela de segurança
         Update sg_autenticacao set
             sq_unidade            = p_unidade,
             sq_localizacao        = p_localizacao,
             gestor_seguranca      = coalesce(p_gestor_seguranca,gestor_seguranca),
             gestor_sistema        = coalesce(p_gestor_sistema,gestor_sistema),
             email                 = p_email,
             tipo_autenticacao     = p_tipo_autenticacao                             
         where sq_pessoa      = p_chave;
       End If;
          
   Elsif p_operacao = 'E' Then
      -- Remove o registro na tabela de segurança
      delete from sg_autenticacao where sq_pessoa = p_chave;
        
      -- Remove da tabela de pessoas físicas
      delete from co_pessoa_fisica where sq_pessoa = p_chave;

      -- Remove da tabela corporativa de pessoas
      delete from co_pessoa where sq_pessoa = p_chave;
   Else
      If p_operacao = 'T' Then
         -- Ativa registro
         update sg_autenticacao set ativo = 'S' where sq_pessoa = p_chave;
      Elsif p_operacao = 'D' Then
         -- Desativa registro
         update sg_autenticacao set ativo = 'N' where sq_pessoa = p_chave;
      End If;
   End If;
end; $$ language 'plpgsql' volatile;