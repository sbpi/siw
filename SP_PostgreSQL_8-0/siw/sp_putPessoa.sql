create or replace FUNCTION sp_putPessoa
   ( p_operacao            varchar,
     p_cliente             numeric,
     p_restricao           varchar,
     p_tipo_pessoa         numeric,
     p_sq_pessoa           numeric,
     p_cpf                 varchar,
     p_cnpj                varchar,
     p_nome                varchar,
     p_nome_resumido       varchar,
     p_sexo                varchar,
     p_nascimento          date,
     p_rg_numero           varchar,
     p_rg_emissao          date,
     p_rg_emissor          varchar,
     p_passaporte          varchar,
     p_sq_pais_passaporte  numeric,
     p_inscricao_estadual  varchar,
     p_logradouro          varchar,
     p_complemento         varchar,
     p_bairro              varchar,
     p_sq_cidade           numeric,
     p_cep                 varchar,
     p_ddd                 varchar,
     p_nr_telefone         varchar,
     p_nr_fax              varchar,
     p_nr_celular          varchar,
     p_email               varchar,
     p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   
   w_existe          numeric(4);
   w_chave_pessoa    numeric(18) := coalesce(p_sq_pessoa,0);
   w_tipo_fone       numeric(18);
   w_chave_fone      numeric(18);
   w_tipo_endereco   numeric(18);
   w_chave_endereco  numeric(18);
   w_sq_tipo_vinculo numeric(18);
   w_fornecedor      varchar(1);
   w_cliente         varchar(1);
BEGIN
    
   If p_operacao = 'I' Then
      -- Carrega a chave da tabela CO_TIPO_VINCULO
      If p_restricao = 'FORNECEDOR' Then
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Fornecedor' and sq_tipo_pessoa = p_tipo_pessoa and cliente = p_cliente;
         w_fornecedor := 'S';
         w_cliente    := 'N';
      Elsif p_restricao = 'CLIENTE' Then
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Cliente' and sq_tipo_pessoa = p_tipo_pessoa and cliente = p_cliente;
         w_cliente    := 'S';
         w_fornecedor := 'N';
      Else
        select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Outros' and sq_tipo_pessoa = p_tipo_pessoa and cliente = p_cliente;
         w_cliente    := 'N';
         w_fornecedor := 'N';
      End If;
      -- recupera a próxima chave da pessoa
      select sq_pessoa.nextval into w_chave_pessoa from dual;
      
      -- insere os dados da pessoa
      insert into co_pessoa
        (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido, fornecedor, cliente)
      values
        (w_chave_pessoa, p_cliente,     w_sq_tipo_vinculo, p_tipo_pessoa,    p_nome, p_nome_resumido, w_fornecedor, w_cliente);
   Else -- Caso contrário, altera
      update co_pessoa
         set nome          = coalesce(p_nome, nome),
             nome_resumido = coalesce(p_nome_resumido, nome_resumido)
       where sq_pessoa = p_sq_pessoa;
   End If;
   
   If p_tipo_pessoa in (1,3) Then -- Se for pessoa física
      -- Verifica se os dados de pessoa física já existem
      select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
      
      If w_existe = 0 Then -- Se não existir insere
         insert into co_pessoa_fisica
           (sq_pessoa,         nascimento,        rg_numero,            rg_emissor,   rg_emissao,   
            cpf,               passaporte_numero, sq_pais_passaporte,   sexo,         cliente
           )
         values
           (w_chave_pessoa,    p_nascimento,      p_rg_numero,          p_rg_emissor, p_rg_emissao, 
            p_cpf,             p_passaporte,      p_sq_pais_passaporte, p_sexo,       p_cliente
           );
      Else -- Caso contrário, altera
         update co_pessoa_fisica
            set nascimento         = coalesce(p_nascimento, nascimento),
                rg_numero          = coalesce(p_rg_numero, rg_numero),
                rg_emissor         = coalesce(p_rg_emissor, rg_emissor),
                rg_emissao         = coalesce(p_rg_emissao, rg_emissao),
                cpf                = coalesce(p_cpf, cpf),
                passaporte_numero  = coalesce(p_passaporte, passaporte_numero),
                sq_pais_passaporte = coalesce(p_sq_pais_passaporte, sq_pais_passaporte),
                sexo               = coalesce(p_sexo, sexo)
          where sq_pessoa = w_chave_pessoa;
      End If;
   Elsif p_tipo_pessoa = 2 Then
      -- Verifica se os dados de pessoa jurídica já existem
      select count(*) into w_existe from co_pessoa_juridica where sq_pessoa = w_chave_pessoa;
      
      If w_existe = 0 Then -- Se não existir insere
         insert into co_pessoa_juridica
           (sq_pessoa,      cnpj,   inscricao_estadual,   cliente)
         values
           (w_chave_pessoa, p_cnpj, p_inscricao_estadual, p_cliente);
      Else -- Caso contrário, altera
         update co_pessoa_juridica
            set cnpj               = p_cnpj,
                inscricao_estadual = coalesce(p_inscricao_estadual, inscricao_estadual)
          where sq_pessoa = w_chave_pessoa;
      End If;
   End If;
   
   -- Se foi informado o e-mail, grava. Caso contrário, remove.
   select count(*) into w_existe
     from co_pessoa_endereco          a
          inner join co_tipo_endereco b on (a.sq_tipo_endereco = b.sq_tipo_endereco)
    where a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = p_tipo_pessoa
      and b.email          = 'S'
      and b.ativo          = 'S'
      and a.padrao         = 'S';
 
   If w_existe > 0 Then
      select sq_pessoa_endereco into w_chave_endereco
        from co_pessoa_endereco          a
             inner join co_tipo_endereco b on (a.sq_tipo_endereco = b.sq_tipo_endereco)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = p_tipo_pessoa
         and b.email          = 'S'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;
            
   If p_email is not null Then
      If w_existe = 0 Then
         select sq_tipo_endereco into w_tipo_endereco
           from co_tipo_endereco b
          where b.sq_tipo_pessoa = p_tipo_pessoa
            and b.email          = 'S'
            and b.ativo          = 'S';
        
         insert into co_pessoa_endereco
           (sq_pessoa_endereco,         sq_pessoa,      sq_tipo_endereco, logradouro,  
            sq_cidade,                  padrao
           )
         values
           (sq_pessoa_endereco.nextval, w_chave_pessoa, w_tipo_endereco,  p_email, 
            p_sq_cidade,                'S'
           );
      Else
         update co_pessoa_endereco
            set logradouro = p_email,
                sq_cidade  = p_sq_cidade
          where sq_pessoa_endereco = w_chave_endereco;
      End If;
   Else
      If w_existe > 0 Then
         DELETE FROM co_pessoa_endereco where sq_pessoa_endereco = w_chave_endereco;
      End If;
   End If;

   If p_logradouro is not null Then
      -- Grava o endereco
      select count(*) into w_existe
        from co_pessoa_endereco          a
             inner join co_tipo_endereco b on (a.sq_tipo_endereco = b.sq_tipo_endereco)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = p_tipo_pessoa
         and b.nome           = 'Comercial'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
       
      If w_existe = 0 Then
         select sq_tipo_endereco into w_tipo_endereco
           from co_tipo_endereco b
          where b.sq_tipo_pessoa = p_tipo_pessoa
            and b.nome           = 'Comercial'
            and b.ativo          = 'S';
          
         insert into co_pessoa_endereco
           (sq_pessoa_endereco,         sq_pessoa,      sq_tipo_endereco, logradouro,
            complemento,                bairro,         sq_cidade,        cep,
             padrao
           )
         values
           (sq_pessoa_endereco.nextval, w_chave_pessoa, w_tipo_endereco,  p_logradouro, 
            p_complemento,              p_bairro,       p_sq_cidade,      p_cep,
            'S'
           );
      Else
         select sq_pessoa_endereco into w_chave_endereco
           from co_pessoa_endereco          a
                inner join co_tipo_endereco b on (a.sq_tipo_endereco = b.sq_tipo_endereco)
          where a.sq_pessoa      = w_chave_pessoa
            and b.sq_tipo_pessoa = p_tipo_pessoa
            and b.nome           = 'Comercial'
            and b.ativo          = 'S'
            and a.padrao         = 'S';
             
         update co_pessoa_endereco
            set logradouro  = p_logradouro,
                complemento = p_complemento,
                bairro      = p_bairro,
                sq_cidade   = p_sq_cidade,
                cep         = p_cep
          where sq_pessoa_endereco = w_chave_endereco;
      End If;
   End If;

   If p_nr_telefone is not null Then
      -- Grava o telefone
      select count(*) into w_existe
        from co_pessoa_telefone          a
             inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = p_tipo_pessoa
         and b.nome           = 'Comercial'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
       
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = p_tipo_pessoa
            and b.nome           = 'Comercial'
            and b.ativo          = 'S';
          
         insert into co_pessoa_telefone
           (sq_pessoa_telefone,         sq_pessoa,      sq_tipo_telefone, 
            sq_cidade,                  ddd,            numero, 
            padrao
           )
         values
           (sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone, 
            p_sq_cidade,                p_ddd,          p_nr_telefone, 
            'S'
           );
      Else
         select sq_pessoa_telefone into w_chave_fone
           from co_pessoa_telefone          a
                inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
          where a.sq_pessoa      = w_chave_pessoa
            and b.sq_tipo_pessoa = p_tipo_pessoa
            and b.nome           = 'Comercial'
            and b.ativo          = 'S'
            and a.padrao         = 'S';
            
         update co_pessoa_telefone
            set sq_cidade = p_sq_cidade,
                ddd       = p_ddd,
                numero    = p_nr_telefone
          where sq_pessoa_telefone = w_chave_fone;
      End If;
   End If;

   -- Se foi informado o fax, grava. Caso contrário remove.
   select count(*) into w_existe
     from co_pessoa_telefone          a
          inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
    where a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = p_tipo_pessoa
      and b.nome           = 'Fax'
      and b.ativo          = 'S'
      and a.padrao         = 'S';
   
   If w_existe > 0 Then
      select sq_pessoa_telefone into w_chave_fone
        from co_pessoa_telefone          a
             inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = p_tipo_pessoa
         and b.nome           = 'Fax'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;

   If p_nr_fax is not null Then
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = p_tipo_pessoa
            and b.nome           = 'Fax'
            and b.ativo          = 'S';
        
         insert into co_pessoa_telefone
           (sq_pessoa_telefone,         sq_pessoa,      sq_tipo_telefone, 
            sq_cidade,                  ddd,            numero, 
            padrao
           )
         values
           (sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone, 
            p_sq_cidade,                p_ddd,          p_nr_fax, 
            'S'
           );
      Else
         update co_pessoa_telefone
            set sq_cidade = p_sq_cidade,
                ddd       = p_ddd,
                numero    = p_nr_fax
          where sq_pessoa_telefone = w_chave_fone;
      End If;
   Else
      If w_existe > 0 Then
         DELETE FROM co_pessoa_telefone where sq_pessoa_telefone = w_chave_fone;
      End If;
   End If;

   -- Se foi informado o celular, grava. Caso contrário, remove.
   select count(*) into w_existe
     from co_pessoa_telefone          a
          inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
    where a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = p_tipo_pessoa
      and b.nome           = 'Celular'
      and b.ativo          = 'S'
      and a.padrao         = 'S';

   If w_existe > 0 Then
      select sq_pessoa_telefone into w_chave_fone
        from co_pessoa_telefone          a
             inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = p_tipo_pessoa
         and b.nome           = 'Celular'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;

   If p_nr_celular is not null Then
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = p_tipo_pessoa
            and b.nome           = 'Celular'
            and b.ativo          = 'S';
        
         insert into co_pessoa_telefone
           (sq_pessoa_telefone,         sq_pessoa,      sq_tipo_telefone, 
            sq_cidade,                  ddd,            numero, 
            padrao
           )
         values
           (sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone, 
            p_sq_cidade,                p_ddd,          p_nr_celular, 
            'S'
           );
      Else
         update co_pessoa_telefone
            set sq_cidade = p_sq_cidade,
                ddd       = p_ddd,
                numero    = p_nr_celular
          where sq_pessoa_telefone = w_chave_fone;
      End If;
   Else
      If w_existe > 0 Then
         DELETE FROM co_pessoa_telefone where sq_pessoa_telefone = w_chave_fone;
      End If;
   End If;
   p_chave_nova := w_chave_pessoa;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;