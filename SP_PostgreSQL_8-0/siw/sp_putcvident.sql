create or replace FUNCTION SP_PutCVIdent
   (p_operacao            varchar,
    p_cliente             numeric,
    p_chave               numeric,
    p_nome                varchar,
    p_nome_resumido       varchar,
    p_foto                varchar,
    p_tamanho             numeric,
    p_tipo                varchar,
    p_nome_original       varchar,    
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
    p_sq_pais_passaporte  numeric,
    p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   w_existe         numeric(18);
   w_chave          numeric(18);
   w_foto           numeric(18);
BEGIN
   w_chave := p_chave;
   w_foto  := null;
   
   If nvl(p_chave,0) = 0 Then
      select count(*)       into w_existe from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
      If w_existe > 0 Then
         -- Recupera a chave da pessoa
         select sq_pessoa into w_chave from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
         update co_pessoa
            set nome          = p_nome,
                nome_resumido = p_nome_resumido
          where sq_pessoa = w_chave;         
      Else
         -- Recupera a próxima chave
         select nextVal('sq_pessoa') into w_chave;
         -- Insere registro em CO_PESSOA
         insert into co_pessoa (
                sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,
                sq_tipo_pessoa, nome,          nome_resumido)
        (select
               w_chave,        p_cliente,     null,
               sq_tipo_pessoa, p_nome,        p_nome_resumido
          from co_tipo_pessoa
         where ativo         = 'S'
           and nome          = 'Física'
         );
      End If;
   Else
     -- Altera o nome e o nome resumido na tabela de pessoas
     update co_pessoa
        set nome          = p_nome,
            nome_resumido = p_nome_resumido
      where sq_pessoa = w_chave;
   End If;
   
   
   -- Verifica se existe dado para a pessoa na tabela de pessoas físicas
   select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave;
   
   -- Insere ou altera, dependendo do resultado
   If w_existe = 0 Then
      insert into co_pessoa_fisica
        (sq_pessoa,            nascimento,          rg_numero,            rg_emissor, 
         rg_emissao,           cpf,                 sq_cidade_nasc,       passaporte_numero,   
         sq_pais_passaporte,   sexo,                cliente,              sq_formacao,
         sq_etnia,             sq_deficiencia)
      values
        (w_chave,              p_nascimento,        p_rg_numero,          p_rg_emissor, 
         p_rg_emissao,         p_cpf,               p_cidade,             p_passaporte_numero, 
         p_sq_pais_passaporte, p_sexo,              p_cliente,            p_sq_formacao,
         p_sq_etnia,           p_sq_deficiencia);
   Else
      update co_pessoa_fisica
         set nascimento         = p_nascimento,
             rg_numero          = p_rg_numero,
             rg_emissor         = p_rg_emissor,
             rg_emissao         = p_rg_emissao,
             cpf                = p_cpf,
             sq_cidade_nasc     = p_cidade,
             passaporte_numero  = p_passaporte_numero,
             sq_pais_passaporte = p_sq_pais_passaporte,
             sexo               = p_sexo,
             sq_formacao        = p_sq_formacao,
             sq_etnia           = p_sq_etnia,
             sq_deficiencia     = p_sq_deficiencia
       where sq_pessoa = w_chave;
   End If;

   -- Verifica se existe dado para a pessoa na tabela principal de currículo
   select count(*) into w_existe from cv_pessoa where sq_pessoa = w_chave;
   
   -- Insere ou altera, dependendo do resultado
   If w_existe = 0 Then
      insert into cv_pessoa
        (sq_pessoa, cliente, sq_estado_civil, inclusao, alteracao)
      values
        (w_chave, p_cliente, p_sq_estado_civil, now(), now());
   Else
      -- Recupera a foto atual
      select sq_siw_arquivo into w_foto from cv_pessoa where sq_pessoa = w_chave;
      
      update cv_pessoa
         set sq_estado_civil = p_sq_estado_civil,
             alteracao       = now()
       where sq_pessoa = w_chave;
   End If;
   
   -- Verifica se é necessário gravar a foto
   If p_foto is not null Then
      If w_foto is null Then
         -- Recupera a próxima chave
         select nextVal('sq_siw_arquivo') into w_foto;
         
         -- Insere registro em SIW_ARQUIVO
         insert into siw_arquivo
           (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
         values
           (w_foto, p_cliente, 'Fotografia', 'Fotografia associada ao CV.', now(), p_tamanho, p_tipo, p_foto, p_nome_original);
           
         -- Associa a fotografia ao CV
         update cv_pessoa set sq_siw_arquivo = w_foto where sq_pessoa = w_chave;
      Else
         update siw_arquivo set
            inclusao = now(),
            caminho  = p_foto,
            tamanho  = p_tamanho,
            tipo     = p_tipo,
            nome_original = p_nome_original
         where sq_siw_arquivo = w_foto;
      End If;
   End If;
   
   commit;
   
   -- Devolve a chave
   p_chave_nova := w_chave;
   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;