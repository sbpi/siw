create or replace FUNCTION SP_PutAcordoPreposto
   ( l_operacao            varchar,
     p_restricao           varchar,
     p_chave               numeric,
     p_cliente           numeric,
     p_sq_pessoa           numeric,
     p_cpf                 varchar,
     p_nome                varchar,
     p_nome_resumido       varchar,
     p_sexo                varchar,
     p_rg_numero           varchar,
     p_rg_emissao          date,
     p_rg_emissor          varchar  
   ) RETURNS VOID AS $$
DECLARE
   
   w_sg_modulo       varchar(10);
   w_existe          numeric(4);
   w_chave_pessoa    numeric(18) := Nvl(p_sq_pessoa,0);
   w_sq_tipo_pessoa  numeric(18);
   w_sq_tipo_vinculo numeric(18);
BEGIN
   -- Recupera o módulo ao qual a solicitação pertence
   select c.sigla into w_sg_modulo
     from siw_solicitacao         a
          inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
            inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
    where sq_siw_solicitacao = p_chave;

   -- Carrega a chave da tabela CO_TIPO_PESSOA
   select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa where nome = 'Física';
   
   select count(*) into w_existe from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
   If w_existe > 0 Then
      select sq_pessoa into w_chave_pessoa from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
   End If;
    
   If w_chave_pessoa = 0 Then -- Se a chave da pessoa não foi informada, insere

      -- Verifica se o tipo do acordo e carrega a chave da tabela CO_TIPO_VINCULO
      If substr(p_restricao,1,3) = 'GCR' or substr(p_restricao,1,5) = 'PJCAD' Then
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Cliente' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_cliente;
      Elsif substr(p_restricao,1,3) = 'GCD' Then
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Fornecedor' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_cliente;
      Elsif substr(p_restricao,1,3) = 'GCP' Then
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Parceiro' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_cliente;
      End If;
     
      -- recupera a próxima chave da pessoa
      select sq_pessoa.nextval into w_chave_pessoa from dual;
      
      -- insere os dados da pessoa
      insert into co_pessoa
        (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
      values
        (w_chave_pessoa, p_cliente,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);

      -- Grava dados complementares, dependendo do tipo de acordo
      If substr(p_restricao,1,3) = 'GCR' or substr(p_restricao,1,5) = 'PJCAD' Then
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) = 'GCD' Then
         update co_pessoa set fornecedor = 'S' where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) = 'GCP' Then
         update co_pessoa set parceiro = 'S'   where sq_pessoa = w_chave_pessoa;
      End If;
   Else -- Caso contrário, altera
      update co_pessoa
         set nome          = p_nome,
             nome_resumido = p_nome_resumido
       where sq_pessoa = w_chave_pessoa;

      -- Grava dados complementares, dependendo do tipo de acordo
      If substr(p_restricao,1,3) = 'GCR' or substr(p_restricao,1,5) = 'PJCAD' Then
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) = 'GCD' Then
         update co_pessoa set fornecedor = 'S' where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) = 'GCP' Then
         update co_pessoa set parceiro = 'S'   where sq_pessoa = w_chave_pessoa;
      End If;
   End If;
   
   -- Verifica se os dados de pessoa física já existem
   select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
    
   If w_existe = 0 Then -- Se não existir insere
      insert into co_pessoa_fisica
        (sq_pessoa,      cpf,   sexo,   rg_numero,   rg_emissor, rg_emissao,     cliente)
      values
        (w_chave_pessoa, p_cpf, p_sexo, p_rg_numero, p_rg_emissor, p_rg_emissao, p_cliente);
   Else -- Caso contrário, altera
      update co_pessoa_fisica
         set cpf                = p_cpf,
             sexo               = p_sexo,
             rg_numero          = p_rg_numero,
             rg_emissor         = p_rg_emissor,
             rg_emissao         = p_rg_emissao
       where sq_pessoa = w_chave_pessoa;
   End If;
   
   -- Atualiza o preposto do contrato
   If w_sg_modulo = 'AC' Then
      update ac_acordo set preposto = w_chave_pessoa where sq_siw_solicitacao = p_chave;
   Elsif w_sg_modulo = 'PR' Then
      update pj_projeto set preposto = w_chave_pessoa where sq_siw_solicitacao = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;