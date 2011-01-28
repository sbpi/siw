create or replace FUNCTION SP_PutRamalUsuario
   (p_operacao            varchar,
    p_cliente             numeric,
    p_chave               numeric,
    p_nome                varchar,
    p_nome_resumido       varchar,
    p_nascimento          date,
    p_sexo                varchar,
    p_sq_estado_civil     numeric,
    p_sq_formacao         numeric,
    p_sq_etnia            numeric,
    p_sq_deficiencia      numeric,
    p_cidade              numeric,
    p_rg_numero           varchar,
    p_rg_emissor          varchar,
    p_rg_emissao          date,
    p_cpf                 varchar,
    p_passaporte_numero   varchar,
    p_sq_pais_passaporte  numeric     
   ) RETURNS VOID AS $$
DECLARE
   w_existe numeric(18);
   w_chave  numeric(18);
BEGIN

   -- Altera o nome e o nome resumido na tabela de pessoas
   update co_pessoa
      set nome          = p_nome,
          nome_resumido = p_nome_resumido
    where sq_pessoa = p_chave;
    
   -- Verifica se existe dado para a pessoa na tabela de pessoas físicas
   select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = p_chave;
   
   -- Insere ou altera, dependendo do resultado
   If w_existe = 0 Then
      insert into co_pessoa_fisica
        (sq_pessoa,            nascimento,          rg_numero,            rg_emissor, 
         rg_emissao,           cpf,                 sq_cidade_nasc,       passaporte_numero,   
         sq_pais_passaporte,   sq_etnia,            sq_deficiencia,       sexo, 
         cliente,              sq_formacao)
      values
        (p_chave,              p_nascimento,        p_rg_numero,          p_rg_emissor, 
         p_rg_emissao,         p_cpf,               p_cidade,             p_passaporte_numero, 
         p_sq_pais_passaporte, p_sq_etnia,          p_sq_deficiencia,     p_sexo, 
         p_cliente,            p_sq_formacao);
   Else
      update co_pessoa_fisica
         set nascimento         = p_nascimento,
             rg_numero          = p_rg_numero,
             rg_emissor         = p_rg_emissor,
             rg_emissao         = p_rg_emissao,
             cpf                = p_cpf,
             sq_cidade_nasc      = p_cidade,
             passaporte_numero  = p_passaporte_numero,
             sq_pais_passaporte = p_sq_pais_passaporte,
             sq_etnia           = p_sq_etnia,
             sq_deficiencia     = p_sq_deficiencia,
             sexo               = p_sexo,
             sq_formacao        = p_sq_formacao
       where sq_pessoa = p_chave;
   End If;

   -- Verifica se existe dado para a pessoa na tabela principal de currículo
   select count(*) into w_existe from cv_pessoa where sq_pessoa = p_chave;
   
   -- Insere ou altera, dependendo do resultado
   If w_existe = 0 Then
      insert into cv_pessoa
        (sq_pessoa, cliente, sq_estado_civil, inclusao, alteracao)
      values
        (p_chave, p_cliente, p_sq_estado_civil, now(), now());
   Else
      update cv_pessoa
         set sq_estado_civil = p_sq_estado_civil,
             alteracao       = now()
       where sq_pessoa = p_chave;
   End If;
   commit;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;