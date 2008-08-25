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
     p_matricula           in varchar2  default null,
     p_rg_numero           in varchar2  default null,
     p_rg_emissao          in date      default null,
     p_rg_emissor          in varchar2  default null,
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
   w_tipo_fone       number(18);
   w_chave_fone      number(18);
   w_chave_conta     number(18);
   w_forma_pagamento varchar2(10) := null;
begin
   -- Se a forma de pagamento foi informada, recupera a sigla
   If p_sq_forma_pag is not null Then
      select sigla into w_forma_pagamento from co_forma_pagamento where sq_forma_pagamento = p_sq_forma_pag;
   End If;

   -- Verifica se é a pessoa já existe
   select count(*) into w_existe from co_pessoa_fisica where cliente = p_chave_aux and cpf = p_cpf;
   If w_existe > 0 Then
      select sq_pessoa into w_chave_pessoa from co_pessoa_fisica where cliente = p_chave_aux and cpf = p_cpf;
   Else
      w_chave_pessoa := 0;
   End If;

   If w_chave_pessoa = 0 Then -- Se a chave da pessoa não foi informada, insere

      -- recupera a próxima chave da pessoa
      select sq_pessoa.nextval into w_chave_pessoa from dual;

      -- insere os dados da pessoa
      insert into co_pessoa
             (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
      (select w_chave_pessoa, p_chave_aux,   p_vinculo,         sq_tipo_pessoa,   p_nome, p_nome_resumido
         from co_tipo_pessoa a
        where a.nome = 'Física'
       );
   Else -- Caso contrário, altera
      update co_pessoa
         set nome            = Nvl(p_nome, nome),
             nome_resumido   = Nvl(p_nome_resumido, nome_resumido),
             sq_tipo_vinculo = Nvl(p_vinculo, sq_tipo_vinculo)
       where sq_pessoa = w_chave_pessoa;
   End If;

   -- Verifica se os dados de pessoa física já existem
   select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
    If w_existe = 0 Then -- Se não existir insere
      insert into co_pessoa_fisica
        (sq_pessoa,      rg_numero,   rg_emissor,   rg_emissao,   cpf,   sexo,   cliente)
      values
        (w_chave_pessoa, p_rg_numero, p_rg_emissor, p_rg_emissao, p_cpf, p_sexo, p_chave_aux
        );
   Else -- Caso contrário, altera
      update co_pessoa_fisica
         set rg_numero          = Nvl(p_rg_numero, rg_numero),
             rg_emissor         = Nvl(p_rg_emissor, rg_emissor),
             rg_emissao         = Nvl(p_rg_emissao, rg_emissao),
             cpf                = Nvl(p_cpf, cpf),
             sexo               = Nvl(p_sexo, sexo)
       where sq_pessoa = w_chave_pessoa;
   End If;

   If p_nr_telefone is not null Then
      -- Grava o telefone
      select count(*) into w_existe
        from co_pessoa_telefone          a,
             co_tipo_telefone b
       where (a.sq_tipo_telefone = b.sq_tipo_telefone)
         and a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = 1 -- Pessoa Física
         and b.nome           = 'Comercial'
         and b.ativo          = 'S'
         and a.padrao         = 'S';

      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = 1 -- Pessoa Física
            and b.nome           = 'Comercial'
            and b.ativo          = 'S';

         insert into co_pessoa_telefone
           (sq_pessoa_telefone,         sq_pessoa,      sq_tipo_telefone,
            sq_cidade,                  ddd,            numero,
            padrao
           )
         (select 
            sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone,
            a.sq_cidade_padrao,         p_ddd,          p_nr_telefone,
            'S'
            from siw_cliente a
           where a.sq_pessoa = p_chave_aux
         );
      Else
         select sq_pessoa_telefone into w_chave_fone
           from co_pessoa_telefone          a,
                co_tipo_telefone b
          where (a.sq_tipo_telefone = b.sq_tipo_telefone)
            and a.sq_pessoa      = w_chave_pessoa
            and b.sq_tipo_pessoa = 1 -- Pessoa Física
            and b.nome           = 'Comercial'
            and b.ativo          = 'S'
            and a.padrao         = 'S';

         update co_pessoa_telefone
            set ddd       = p_ddd,
                numero    = p_nr_telefone
          where sq_pessoa_telefone = w_chave_fone;
      End If;
   End If;

   -- Se foi informado o fax, grava. Caso contrário remove.
   select count(*) into w_existe
     from co_pessoa_telefone          a,
          co_tipo_telefone b
    where (a.sq_tipo_telefone = b.sq_tipo_telefone)
      and a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = 1 -- Pessoa Física
      and b.nome           = 'Fax'
      and b.ativo          = 'S'
      and a.padrao         = 'S';

   If w_existe > 0 Then
      select sq_pessoa_telefone into w_chave_fone
        from co_pessoa_telefone          a,
             co_tipo_telefone b
       where (a.sq_tipo_telefone = b.sq_tipo_telefone)
         and a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = 1 -- Pessoa Física
         and b.nome           = 'Fax'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;

   If p_nr_fax is not null Then
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = 1 -- Pessoa Física
            and b.nome           = 'Fax'
            and b.ativo          = 'S';

         insert into co_pessoa_telefone
           (sq_pessoa_telefone,         sq_pessoa,      sq_tipo_telefone,
            sq_cidade,                  ddd,            numero,
            padrao
           )
         (select 
            sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone,
            a.sq_cidade_padrao,         p_ddd,          p_nr_fax,
            'S'
            from siw_cliente a
           where a.sq_pessoa = p_chave_aux
         );
      Else
         update co_pessoa_telefone
            set ddd       = p_ddd,
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
     from co_pessoa_telefone          a, 
          co_tipo_telefone b
    where (a.sq_tipo_telefone = b.sq_tipo_telefone)
      and a.sq_pessoa      = w_chave_pessoa
      and b.sq_tipo_pessoa = 1 -- Pessoa Física
      and b.nome           = 'Celular'
      and b.ativo          = 'S'
      and a.padrao         = 'S';

   If w_existe > 0 Then
      select sq_pessoa_telefone into w_chave_fone
        from co_pessoa_telefone          a, 
             co_tipo_telefone b
       where (a.sq_tipo_telefone = b.sq_tipo_telefone)
         and a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = 1 -- Pessoa Física
         and b.nome           = 'Celular'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
   End If;

   If p_nr_celular is not null Then
      If w_existe = 0 Then
         select sq_tipo_telefone into w_tipo_fone
           from co_tipo_telefone b
          where b.sq_tipo_pessoa = 1 -- Pessoa Física
            and b.nome           = 'Celular'
            and b.ativo          = 'S';

         insert into co_pessoa_telefone
           (sq_pessoa_telefone,         sq_pessoa,      sq_tipo_telefone,
            sq_cidade,                  ddd,            numero,
            padrao
           )
         (select 
            sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone,
            a.sq_cidade_padrao,         p_ddd,          p_nr_celular,
            'S'
           from siw_cliente a
          where a.sq_pessoa = p_chave_aux
         );
      Else
         update co_pessoa_telefone
            set ddd       = p_ddd,
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
