create or replace procedure SP_PutViagemOutra
   ( p_operacao            in varchar2,
     p_restricao           in varchar2,
     p_chave               in number    default null,
     p_chave_aux           in number    default null,
     p_sq_pessoa           in number    default null,
     p_cpf                 in varchar2  default null,
     p_nome                in varchar2  default null,
     p_nome_resumido       in varchar2  default null,
     p_sexo                in varchar2  default null,
     p_vinculo             in number    default null,
     p_rg_numero           in varchar2  default null,
     p_rg_emissao          in date      default null,
     p_rg_emissor          in varchar2  default null,
     p_passaporte          in varchar2  default null,
     p_sq_pais_passaporte  in number    default null,
     p_logradouro          in varchar2  default null,
     p_complemento         in varchar2  default null,
     p_bairro              in varchar2  default null,
     p_sq_cidade           in number    default null,
     p_cep                 in varchar2  default null,
     p_email               in varchar2  default null,
     p_ddd                 in varchar2  default null,
     p_nr_telefone         in varchar2  default null,
     p_nr_fax              in varchar2  default null,
     p_nr_celular          in varchar2  default null,
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
     p_sq_forma_pag        in number    default null
   ) is

   w_existe          number(4);
   w_chave_pessoa    number(18) := Nvl(p_sq_pessoa,0);
   w_tipo_pessoa     number(18);
   w_tipo_endereco   number(18);
   w_chave_endereco  number(18);
   w_tipo_fone       number(18);
   w_chave_fone      number(18);
   w_chave_conta     number(18);
   w_forma_pagamento varchar2(10) := null;
begin
   -- Se a forma de pagamento foi informada, recupera a sigla
   If p_sq_forma_pag is not null Then
      select sigla into w_forma_pagamento from co_forma_pagamento where sq_forma_pagamento = p_sq_forma_pag;
   End If;

   -- Altera os dados do beneficiário da viagem
   update co_pessoa
      set nome            = Nvl(p_nome, nome),
          nome_resumido   = Nvl(p_nome_resumido, nome_resumido),
          sq_tipo_vinculo = Nvl(p_vinculo, sq_tipo_vinculo)
   where  sq_pessoa = w_chave_pessoa;
   
   -- Recupera o tipo de pessoa (física nacional ou física estrangeira)
   select sq_tipo_pessoa into w_tipo_pessoa from co_pessoa where sq_pessoa = w_chave_pessoa;

   -- Verifica se os dados de pessoa física já existem
   select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
    If w_existe = 0 Then -- Se não existir insere
      insert into co_pessoa_fisica
        (sq_pessoa,      rg_numero,   rg_emissor,   rg_emissao,   passaporte_numero, sq_pais_passaporte,   cpf,   sexo,   cliente)
      values
        (w_chave_pessoa, p_rg_numero, p_rg_emissor, p_rg_emissao, p_passaporte,      p_sq_pais_passaporte, p_cpf, p_sexo, p_chave_aux
        );
   Else -- Caso contrário, altera
      update co_pessoa_fisica
         set rg_numero          = Nvl(p_rg_numero, rg_numero),
             rg_emissor         = Nvl(p_rg_emissor, rg_emissor),
             rg_emissao         = Nvl(p_rg_emissao, rg_emissao),
             passaporte_numero  = p_passaporte,
             sq_pais_passaporte = p_sq_pais_passaporte,
             cpf                = Nvl(p_cpf, cpf),
             sexo               = Nvl(p_sexo, sexo)
       where sq_pessoa = w_chave_pessoa;
   End If;

   -- Se foi informado o e-mail, grava. Caso contrário, remove.
   select count(*) into w_existe
     from co_pessoa_endereco          a
          inner join co_tipo_endereco b on (a.sq_tipo_endereco = b.sq_tipo_endereco)
    where a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = w_tipo_pessoa
      and b.email          = 'S'
      and b.ativo          = 'S'
      and a.padrao         = 'S';
 
   If w_existe > 0 Then
      select sq_pessoa_endereco into w_chave_endereco
        from co_pessoa_endereco          a
             inner join co_tipo_endereco b on (a.sq_tipo_endereco = b.sq_tipo_endereco)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = w_tipo_pessoa
         and b.email          = 'S'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;
            
   If p_email is not null Then
      If w_existe = 0 Then
         select sq_tipo_endereco into w_tipo_endereco
           from co_tipo_endereco b
          where b.sq_tipo_pessoa = w_tipo_pessoa
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
         and b.sq_tipo_pessoa = w_tipo_pessoa
         and b.nome           = 'Comercial'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
       
      If w_existe = 0 Then
         select sq_tipo_endereco into w_tipo_endereco
           from co_tipo_endereco b
          where b.sq_tipo_pessoa = w_tipo_pessoa
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
            and b.sq_tipo_pessoa = w_tipo_pessoa
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
         and b.sq_tipo_pessoa = w_tipo_pessoa
         and b.nome           = 'Comercial'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
       
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = w_tipo_pessoa
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
            and b.sq_tipo_pessoa = w_tipo_pessoa
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
      and b.sq_tipo_pessoa = w_tipo_pessoa
      and b.nome           = 'Fax'
      and b.ativo          = 'S'
      and a.padrao         = 'S';
   
   If w_existe > 0 Then
      select sq_pessoa_telefone into w_chave_fone
        from co_pessoa_telefone          a
             inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = w_tipo_pessoa
         and b.nome           = 'Fax'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;

   If p_nr_fax is not null Then
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = w_tipo_pessoa
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

   -- Se foi informado o celular, grava. Caso contrário, remove.
   select count(*) into w_existe
     from co_pessoa_telefone          a
          inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
    where a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = w_tipo_pessoa
      and b.nome           = 'Celular'
      and b.ativo          = 'S'
      and a.padrao         = 'S';

   If w_existe > 0 Then
      select sq_pessoa_telefone into w_chave_fone
        from co_pessoa_telefone          a
             inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = w_tipo_pessoa
         and b.nome           = 'Celular'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;

   If p_nr_celular is not null Then
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = w_tipo_pessoa
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

   If p_sq_agencia is not null and p_nr_conta is not null Then
      -- Se foi informado o banco, grava
      select count(*) into w_existe
        from co_pessoa_conta a
       where a.sq_pessoa  = w_chave_pessoa
         and a.tipo_conta = 1 -- Conta corrente
         and a.ativo      = 'S'
         and a.padrao     = 'S';

      If w_existe = 0 Then
         insert into co_pessoa_conta
           (sq_pessoa_conta,                  sq_pessoa,      sq_agencia,  operacao,
            numero,                           ativo,          padrao,      tipo_conta
           )
         values
           (sq_pessoa_conta_bancaria.nextval, w_chave_pessoa, p_sq_agencia, p_op_conta,
            p_nr_conta,                       'S',            'S',          '1'
           );
      Else
         select sq_pessoa_conta into w_chave_conta
           from co_pessoa_conta a
          where a.sq_pessoa  = w_chave_pessoa
            and a.tipo_conta = 1 -- Conta corrente
            and a.ativo      = 'S'
            and a.padrao     = 'S';

         update co_pessoa_conta
            set sq_agencia = p_sq_agencia,
                operacao   = p_op_conta,
                numero     = p_nr_conta
          where sq_pessoa_conta = w_chave_conta;
      End If;
   End If;

   -- Atualiza a outra parte
   update pd_missao 
     set sq_pessoa          = w_chave_pessoa,
         sq_forma_pagamento = p_sq_forma_pag,
         sq_agencia         = null,
         operacao_conta     = null,
         numero_conta       = null,
         sq_pais_estrang    = null,
         aba_code           = null,
         swift_code         = null,
         endereco_estrang   = null,
         banco_estrang      = null,
         agencia_estrang    = null,
         cidade_estrang     = null,
         informacoes        = null,
         codigo_deposito    = null
   where sq_siw_solicitacao = p_chave;
   
   If w_forma_pagamento in ('CREDITO','DEPOSITO') Then
      update pd_missao
         set sq_agencia     = p_sq_agencia,
             operacao_conta = p_op_conta,
             numero_conta   = p_nr_conta
      where sq_siw_solicitacao = p_chave;
   Elsif w_forma_pagamento = 'ORDEM' Then
      update pd_missao
         set sq_agencia     = p_sq_agencia
      where sq_siw_solicitacao = p_chave;
   Elsif w_forma_pagamento = 'EXTERIOR' Then
      update pd_missao
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

end SP_PutViagemOutra;
/
