create or replace procedure SP_PutAcordoOutra
   ( p_operacao            in varchar2,
     p_restricao           in varchar2,
     p_chave               in number    default null,
     p_chave_aux           in number    default null,
     p_sq_pessoa           in number    default null,
     p_cpf                 in varchar2  default null,
     p_cnpj                in varchar2  default null,
     p_nome                in varchar2  default null,
     p_nome_resumido       in varchar2  default null,
     p_sexo                in varchar2  default null,
     p_nascimento          in date      default null,
     p_rg_numero           in varchar2  default null,
     p_rg_emissao          in date      default null,
     p_rg_emissor          in varchar2  default null,
     p_passaporte          in varchar2  default null,
     p_sq_pais_passaporte  in number    default null,
     p_inscricao_estadual  in varchar2  default null,
     p_logradouro          in varchar2  default null,
     p_complemento         in varchar2  default null,
     p_bairro              in varchar2  default null,
     p_sq_cidade           in number    default null,
     p_cep                 in varchar2  default null,
     p_ddd                 in varchar2  default null,
     p_nr_telefone         in varchar2  default null,
     p_nr_fax              in varchar2  default null,
     p_nr_celular          in varchar2  default null,
     p_email               in varchar2  default null,
     p_sq_agencia          in number    default null,
     p_op_conta            in varchar2  default null,
     p_nr_conta            in varchar2  default null,
     p_sq_pais_estrang     in number    default null,
     p_aba_code            in varchar2  default null,
     p_swift_code          in varchar2  default null,
     p_endereco_estrang    in varchar2  default null,
     p_banco_estrang       in varchar2  default null,
     p_agencia_estrang     in varchar2  default null,
     p_cidade_estrang      in varchar2  default null,
     p_informacoes         in varchar2  default null,
     p_codigo_deposito     in varchar2  default null,
     p_pessoa_atual        in number    default null,
     p_conta               in number    default null
   ) is
   
   w_sg_modulo       varchar2(10);
   w_existe          number(4);
   w_chave_pessoa    number(18) := coalesce(p_sq_pessoa,0);
   w_tipo_fone       number(18);
   w_chave_fone      number(18);
   w_tipo_endereco   number(18);
   w_chave_endereco  number(18);
   w_chave_conta     number(18);
   w_sq_tipo_pessoa  number(18);
   w_forma_pagamento varchar2(10);
   w_sq_tipo_vinculo number(18);
begin
   -- Recupera o m�dulo ao qual a solicita��o pertence
   select case when b.sq_siw_solicitacao is not null then 'PR'
               when c.sq_siw_solicitacao is not null then 'AC'
               when d.sq_siw_solicitacao is not null then 'FN'
          end into w_sg_modulo
     from siw_solicitacao         a
          left join pj_projeto    b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
          left join ac_acordo     c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
          left join fn_lancamento d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
    where a.sq_siw_solicitacao = p_chave;

   -- Recupera a forma de pagamento
   If w_sg_modulo = 'AC' Then
      select acentos(b.sigla) into w_forma_pagamento
        from ac_acordo                     a
             inner join co_forma_pagamento b on (a.sq_forma_pagamento = b.sq_forma_pagamento)
       where sq_siw_solicitacao = p_chave;
   Elsif w_sg_modulo = 'FN' Then
      select acentos(b.sigla) into w_forma_pagamento
        from fn_lancamento                 a
             inner join co_forma_pagamento b on (a.sq_forma_pagamento = b.sq_forma_pagamento)
       where sq_siw_solicitacao = p_chave;
    End If;

   -- Verifica se � pessoa f�sica ou jur�dica e carrega a chave da tabela CO_TIPO_PESSOA
   If p_sq_pessoa is not null Then
      select sq_tipo_pessoa into w_sq_tipo_pessoa from co_pessoa where sq_pessoa = p_sq_pessoa;
      w_chave_pessoa := p_sq_pessoa;
   Elsif p_cpf is not null Then 
      select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa   where substr(nome,1,1) = 'F';
      select count(*)       into w_existe         from co_pessoa_fisica where cliente = p_chave_aux and cpf = p_cpf;
      If w_existe > 0 Then
         select min(sq_pessoa) into w_chave_pessoa from co_pessoa_fisica where cliente = p_chave_aux and cpf = p_cpf;
      Else
         w_chave_pessoa := 0;
      End If;
   Else 
      select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa     where substr(nome,1,1) = 'J';
      select count(*)       into w_existe         from co_pessoa_juridica where cliente = p_chave_aux and cnpj = p_cnpj;
      If w_existe > 0 Then
         select min(sq_pessoa) into w_chave_pessoa from co_pessoa_juridica where cliente = p_chave_aux and cnpj = p_cnpj;
      Else
         w_chave_pessoa := 0;
      End If;
   End If;
   
   If w_chave_pessoa = 0 Then -- Se a chave da pessoa n�o foi informada, insere

      -- Carrega a chave da tabela CO_TIPO_VINCULO, dependendo do tipo da solicita��o
      If substr(p_restricao,1,3) in ('GCR','FNR') or substr(p_restricao,1,5) = 'PJCAD' Then
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Cliente' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_chave_aux;
      Elsif substr(p_restricao,1,3) in ('GCD','FND') or substr(p_restricao,1,2) = 'PJ' Then
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Fornecedor' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_chave_aux;
      Elsif substr(p_restricao,1,3) = 'GCP' Then
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Parceiro' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_chave_aux;
      End If;
     
      -- recupera a pr�xima chave da pessoa
      select sq_pessoa.nextval into w_chave_pessoa from dual;
      
      -- insere os dados da pessoa
      insert into co_pessoa
        (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
      values
        (w_chave_pessoa, p_chave_aux,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);

      -- Grava dados complementares, dependendo do tipo de acordo
      If substr(p_restricao,1,3) in ('GCR','FNR') or substr(p_restricao,1,5) = 'PJCAD' Then
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) in ('GCD','FND') or substr(p_restricao,1,2) = 'PJ' Then
         update co_pessoa set fornecedor = 'S' where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) = 'GCP' Then
         update co_pessoa set parceiro = 'S'   where sq_pessoa = w_chave_pessoa;
      End If;
   Else -- Caso contr�rio, altera
      update co_pessoa
         set nome          = coalesce(p_nome, nome),
             nome_resumido = coalesce(p_nome_resumido, nome_resumido)
       where sq_pessoa = w_chave_pessoa;

      -- Grava dados complementares, dependendo do tipo de acordo
      If substr(p_restricao,1,3) in ('GCR','FNR') or substr(p_restricao,1,5) = 'PJCAD' Then
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) in ('GCD','FND') or substr(p_restricao,1,2) = 'PJ' Then
         update co_pessoa set fornecedor = 'S' where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) = 'GCP' Then
         update co_pessoa set parceiro = 'S'   where sq_pessoa = w_chave_pessoa;
      End If;
   End If;
   
   If p_cpf is not null then -- Se for pessoa f�sica
      -- Verifica se os dados de pessoa f�sica j� existem
      select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
      
      If w_existe = 0 Then -- Se n�o existir insere
         insert into co_pessoa_fisica
           (sq_pessoa,         nascimento,        rg_numero,            rg_emissor,   rg_emissao,   
            cpf,               passaporte_numero, sq_pais_passaporte,   sexo,         cliente
           )
         values
           (w_chave_pessoa,    p_nascimento,      p_rg_numero,          p_rg_emissor, p_rg_emissao, 
            p_cpf,             p_passaporte,      p_sq_pais_passaporte, p_sexo,       p_chave_aux
           );
      Else -- Caso contr�rio, altera
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
   Else
      -- Verifica se os dados de pessoa jur�dica j� existem
      select count(*) into w_existe from co_pessoa_juridica where sq_pessoa = w_chave_pessoa;
      
      If w_existe = 0 Then -- Se n�o existir insere
         insert into co_pessoa_juridica
           (sq_pessoa,      cnpj,   inscricao_estadual,   cliente)
         values
           (w_chave_pessoa, p_cnpj, p_inscricao_estadual, p_chave_aux);
      Else -- Caso contr�rio, altera
         update co_pessoa_juridica
            set cnpj               = p_cnpj,
                inscricao_estadual = coalesce(p_inscricao_estadual, inscricao_estadual)
          where sq_pessoa = w_chave_pessoa;
      End If;
   End If;
   
   -- Se foi informado o e-mail, grava. Caso contr�rio, remove.
   select count(*) into w_existe
     from co_pessoa_endereco          a
          inner join co_tipo_endereco b on (a.sq_tipo_endereco = b.sq_tipo_endereco)
    where a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = w_sq_tipo_pessoa
      and b.email          = 'S'
      and b.ativo          = 'S'
      and a.padrao         = 'S';
 
   If w_existe > 0 Then
      select sq_pessoa_endereco into w_chave_endereco
        from co_pessoa_endereco          a
             inner join co_tipo_endereco b on (a.sq_tipo_endereco = b.sq_tipo_endereco)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = w_sq_tipo_pessoa
         and b.email          = 'S'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;
            
   If p_email is not null Then
      If w_existe = 0 Then
         select sq_tipo_endereco into w_tipo_endereco
           from co_tipo_endereco b
          where b.sq_tipo_pessoa = w_sq_tipo_pessoa
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
         delete co_pessoa_endereco where sq_pessoa_endereco = w_chave_endereco;
      End If;
   End If;

   If p_logradouro is not null Then
      -- Grava o endereco
      select count(*) into w_existe
        from co_pessoa_endereco          a
             inner join co_tipo_endereco b on (a.sq_tipo_endereco = b.sq_tipo_endereco)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = w_sq_tipo_pessoa
         and b.nome           = 'Comercial'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
       
      If w_existe = 0 Then
         select sq_tipo_endereco into w_tipo_endereco
           from co_tipo_endereco b
          where b.sq_tipo_pessoa = w_sq_tipo_pessoa
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
            and b.sq_tipo_pessoa = w_sq_tipo_pessoa
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
         and b.sq_tipo_pessoa = w_sq_tipo_pessoa
         and b.nome           = 'Comercial'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
       
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = w_sq_tipo_pessoa
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
            and b.sq_tipo_pessoa = w_sq_tipo_pessoa
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

   -- Se foi informado o fax, grava. Caso contr�rio remove.
   select count(*) into w_existe
     from co_pessoa_telefone          a
          inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
    where a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = w_sq_tipo_pessoa
      and b.nome           = 'Fax'
      and b.ativo          = 'S'
      and a.padrao         = 'S';
   
   If w_existe > 0 Then
      select sq_pessoa_telefone into w_chave_fone
        from co_pessoa_telefone          a
             inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = w_sq_tipo_pessoa
         and b.nome           = 'Fax'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;

   If p_nr_fax is not null Then
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = w_sq_tipo_pessoa
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
         delete co_pessoa_telefone where sq_pessoa_telefone = w_chave_fone;
      End If;
   End If;

   -- Se foi informado o celular, grava. Caso contr�rio, remove.
   select count(*) into w_existe
     from co_pessoa_telefone          a
          inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
    where a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = w_sq_tipo_pessoa
      and b.nome           = 'Celular'
      and b.ativo          = 'S'
      and a.padrao         = 'S';

   If w_existe > 0 Then
      select sq_pessoa_telefone into w_chave_fone
        from co_pessoa_telefone          a
             inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = w_sq_tipo_pessoa
         and b.nome           = 'Celular'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;

   If p_nr_celular is not null Then
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = w_sq_tipo_pessoa
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
         delete co_pessoa_telefone where sq_pessoa_telefone = w_chave_fone;
      End If;
   End If;

   -- Atualiza os dados banc�rios se o benefici�rio n�o existir em SIW_CLIENTE 
   If w_chave_pessoa <> p_chave_aux and p_nr_conta is not null and (p_banco_estrang is not null or p_sq_agencia is not null) Then
      -- Se foi informado o banco, grava
      select count(*) into w_existe
        from co_pessoa_conta a
       where a.sq_pessoa  = w_chave_pessoa
         and a.ativo      = 'S'
         and a.padrao     = 'S';
     
      If w_existe = 0 Then
         insert into co_pessoa_conta
           (sq_pessoa_conta,                  sq_pessoa,      sq_agencia,  operacao,            numero,          ativo,             padrao,           tipo_conta,
            sq_pais_estrang,                  aba_code,       swift_code,  endereco_estrang,    banco_estrang,   agencia_estrang,   cidade_estrang,   informacoes
           )
         values
           (sq_pessoa_conta_bancaria.nextval, w_chave_pessoa, p_sq_agencia, p_op_conta,         p_nr_conta,      'S',               'S',              '1',
            p_sq_pais_estrang,                p_aba_code,     p_swift_code, p_endereco_estrang, p_banco_estrang, p_agencia_estrang, p_cidade_estrang, p_informacoes);
      Else
         select sq_pessoa_conta into w_chave_conta
           from co_pessoa_conta a
          where a.sq_pessoa  = w_chave_pessoa
            and a.ativo      = 'S'
            and a.padrao     = 'S';
            
         If p_sq_agencia is not null Then
            update co_pessoa_conta
               set sq_agencia = p_sq_agencia,
                   operacao   = p_op_conta,
                   numero     = p_nr_conta
            where sq_pessoa_conta = w_chave_conta;
         Elsif p_banco_estrang is not null Then
            update co_pessoa_conta
               set sq_pais_estrang  = p_sq_pais_estrang,
                   aba_code         = p_aba_code,
                   swift_code       = p_swift_code,
                   endereco_estrang = p_endereco_estrang,
                   banco_estrang    = p_banco_estrang,
                   agencia_estrang  = p_agencia_estrang,
                   cidade_estrang   = p_cidade_estrang,
                   informacoes      = p_informacoes,
                   numero           = p_nr_conta
            where sq_pessoa_conta = w_chave_conta;
         End If;
      End If;
   End If;
   
   -- Atualiza a outra parte
   If w_sg_modulo = 'AC' Then
      update ac_acordo 
        set outra_parte      = w_chave_pessoa,
            sq_agencia       = null,
            operacao_conta   = null,
            numero_conta     = null,
            sq_pais_estrang  = null,
            aba_code         = null,
            swift_code       = null,
            endereco_estrang = null,
            banco_estrang    = null,
            agencia_estrang  = null,
            cidade_estrang   = null,
            informacoes      = null,
            codigo_deposito  = null
      where sq_siw_solicitacao = p_chave;
      
      If coalesce(p_pessoa_atual, w_chave_pessoa) <> w_chave_pessoa Then
         update ac_acordo set preposto = null where sq_siw_solicitacao = p_chave;
      End If;
     
      If w_forma_pagamento in ('CREDITO','DEPOSITO') Then
         update ac_acordo 
            set sq_agencia     = p_sq_agencia,
                operacao_conta = p_op_conta,
                numero_conta   = p_nr_conta
         where sq_siw_solicitacao = p_chave;
      Elsif w_forma_pagamento = 'ORDEM' Then
         update ac_acordo 
            set sq_agencia     = p_sq_agencia
         where sq_siw_solicitacao = p_chave;
      Elsif w_forma_pagamento = 'EXTERIOR' Then
         update ac_acordo 
            set sq_pais_estrang  = p_sq_pais_estrang,
                aba_code         = p_aba_code,
                swift_code       = p_swift_code,
                endereco_estrang = p_endereco_estrang,
                banco_estrang    = p_banco_estrang,
                agencia_estrang  = p_agencia_estrang,
                numero_conta     = p_nr_conta,
                cidade_estrang   = p_cidade_estrang,
                informacoes      = p_informacoes
         where sq_siw_solicitacao = p_chave;
      End If;
   elsif w_sg_modulo = 'FN' Then
      update fn_lancamento
        set pessoa           = w_chave_pessoa,
            sq_agencia       = null,
            operacao_conta   = null,
            numero_conta     = null,
            sq_pais_estrang  = null,
            aba_code         = null,
            swift_code       = null,
            endereco_estrang = null,
            banco_estrang    = null,
            agencia_estrang  = null,
            cidade_estrang   = null,
            informacoes      = null,
            codigo_deposito  = null
      where sq_siw_solicitacao = p_chave;
      
      If w_forma_pagamento in ('CREDITO','DEPOSITO','DEBITO') or w_chave_pessoa = p_chave_aux Then
         update fn_lancamento 
            set sq_tipo_pessoa  = sq_tipo_pessoa,
                sq_agencia      = p_sq_agencia,
                operacao_conta  = p_op_conta,
                numero_conta    = p_nr_conta,
                sq_pessoa_conta = coalesce(p_conta,sq_pessoa_conta),
                codigo_deposito = coalesce(p_codigo_deposito,codigo_deposito)
         where sq_siw_solicitacao = p_chave;
      Elsif w_forma_pagamento = 'CHEQUE' Then
         update fn_lancamento 
            set sq_pessoa_conta = p_conta,
                sq_agencia      = (select a.sq_agencia from co_pessoa_conta a where a.sq_pessoa_conta = p_conta),
                numero_conta    = p_nr_conta
         where sq_siw_solicitacao = p_chave;
      Elsif w_forma_pagamento = 'ORDEM' Then
         update fn_lancamento 
            set sq_agencia     = p_sq_agencia
         where sq_siw_solicitacao = p_chave;
      Elsif w_forma_pagamento = 'EXTERIOR' Then
         update fn_lancamento 
            set sq_pais_estrang  = p_sq_pais_estrang,
                aba_code         = p_aba_code,
                swift_code       = p_swift_code,
                endereco_estrang = p_endereco_estrang,
                banco_estrang    = p_banco_estrang,
                agencia_estrang  = p_agencia_estrang,
                numero_conta     = p_nr_conta,
                cidade_estrang   = p_cidade_estrang,
                informacoes      = p_informacoes
         where sq_siw_solicitacao = p_chave;
      End If;
   Elsif w_sg_modulo = 'PR' Then
      update pj_projeto set outra_parte = w_chave_pessoa where sq_siw_solicitacao = p_chave;
      If coalesce(p_pessoa_atual, w_chave_pessoa) <> w_chave_pessoa Then
         update pj_projeto set preposto = null where sq_siw_solicitacao = p_chave;
         delete pj_projeto_representante where sq_siw_solicitacao = p_chave;
      End If;
   End If;

end SP_PutAcordoOutra;
/
