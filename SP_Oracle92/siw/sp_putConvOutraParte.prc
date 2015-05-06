create or replace procedure SP_PutConvOutraParte
   ( p_operacao                     in varchar2,
     p_restricao                    in varchar2,
     p_sq_acordo_outra_parte        in number    default null,
     p_chave                        in number    default null,
     p_sq_pessoa                    in number    default null,
     p_tipo                         in number    default null,
     p_chave_aux                    in number    default null,
     p_cpf                          in varchar2  default null,
     p_cnpj                         in varchar2  default null,
     p_nome                         in varchar2  default null,
     p_nome_resumido                in varchar2  default null,
     p_sexo                         in varchar2  default null,
     p_nascimento                   in date      default null,
     p_rg_numero                    in varchar2  default null,
     p_rg_emissao                   in date      default null,
     p_rg_emissor                   in varchar2  default null,
     p_passaporte                   in varchar2  default null,
     p_sq_pais_passaporte           in number    default null,     
     p_inscricao_estadual           in varchar2  default null,
     p_logradouro                   in varchar2  default null,
     p_complemento                  in varchar2  default null,
     p_bairro                       in varchar2  default null,
     p_sq_cidade                    in number    default null,
     p_cep                          in varchar2  default null,
     p_ddd                          in varchar2  default null,
     p_nr_telefone                  in varchar2  default null,
     p_nr_fax                       in varchar2  default null,
     p_nr_celular                   in varchar2  default null,
     p_email                        in varchar2  default null,
     p_sq_agencia                   in number    default null,
     p_op_conta                     in varchar2  default null,
     p_nr_conta                     in varchar2  default null,
     p_sq_pais_estrang              in number    default null,
     p_aba_code                     in varchar2  default null,
     p_swift_code                   in varchar2  default null,
     p_endereco_estrang             in varchar2  default null,
     p_banco_estrang                in varchar2  default null,
     p_agencia_estrang              in varchar2  default null,
     p_cidade_estrang               in varchar2  default null,
     p_informacoes                  in varchar2  default null,
     p_codigo_deposito              in varchar2  default null,
     p_pessoa_atual                 in number    default null
   ) is
   
   w_sg_modulo          varchar2(10);
   w_existe             number(18);
   w_chave_pessoa       number(18) := Nvl(p_sq_pessoa,0);
   w_chave_conta        number(18);
   w_forma_pagamento    varchar2(10);
   w_sq_siw_solicitacao number(18);
   w_outra_parte        number(18);
   w_preposto           number(18);
   w_cidade             co_cidade.sq_cidade%type;
   w_pessoa             co_pessoa%rowtype;
begin
   If p_operacao = 'E' Then 
      -- Exclui registro
      select sq_siw_solicitacao, outra_parte into w_sq_siw_solicitacao, w_outra_parte
        from ac_acordo_outra_parte 
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      
      -- Se tiver apenas uma outra parte, atualiza o valor para nulo em AC_ACORDO
      select count(*) into w_existe from ac_acordo_outra_parte where sq_siw_solicitacao = w_sq_siw_solicitacao;
      
      If w_existe = 1 Then
         update ac_acordo set outra_parte = null
          where sq_siw_solicitacao = w_sq_siw_solicitacao;
      Else
         -- Se tiver mais de uma outra parte, atualiza para nulo somente se o registro excluído
         -- estiver gravado na outra parte.
         update ac_acordo set outra_parte = null
          where sq_siw_solicitacao = w_sq_siw_solicitacao
            and outra_parte        = w_outra_parte;
      End If;
      
      delete ac_acordo_outra_rep    where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      delete ac_acordo_outra_parte  where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      
      select count(*) into w_existe
        from ac_acordo_outra_parte
       where sq_siw_solicitacao = w_sq_siw_solicitacao;
      
      If w_existe > 0 Then
         select nvl(a.outra_parte,0), nvl(b.sq_pessoa,0) into w_outra_parte, w_preposto
           from ac_acordo_outra_parte         a
                left join ac_acordo_outra_rep b on (a.sq_acordo_outra_parte = b.sq_acordo_outra_parte and b.tipo = 1)
          where a.sq_siw_solicitacao = w_sq_siw_solicitacao
            and rownum = 1;
         
         If w_outra_parte > 0 Then
            update ac_acordo set outra_parte = w_outra_parte         
            where sq_siw_solicitacao = w_sq_siw_solicitacao;
         End If;
         
         If w_preposto > 0 Then
            update ac_acordo set preposto = w_preposto         
            where sq_siw_solicitacao = w_sq_siw_solicitacao;         
         End If;
      End If;
   Else
       
      -- Grava dados complementares, dependendo do tipo de acordo
      If substr(p_restricao,1,3) in ('GCR','FNR') or substr(p_restricao,1,5) = 'PJCAD' Then
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) in ('GCD','FND') or substr(p_restricao,1,2) = 'PJ' Then
         update co_pessoa set fornecedor = 'S' where sq_pessoa = w_chave_pessoa;
      Elsif substr(p_restricao,1,3) = 'GCP' Then
         update co_pessoa set parceiro = 'S'   where sq_pessoa = w_chave_pessoa;
      End If;

      -- Recupera dados da pessoa informada
      select * into w_pessoa from co_pessoa where sq_pessoa = w_chave_pessoa;
      
      -- Recupera a forma de pagamento do acordo
      select b.sigla into w_forma_pagamento
        from ac_acordo                     a
             inner join co_forma_pagamento b on (a.sq_forma_pagamento = b.sq_forma_pagamento)
       where a.sq_siw_solicitacao = p_chave;

      -- Atualiza os dados da pessoa
      sp_putpessoa(p_operacao           => 'A',
                   p_cliente            => w_pessoa.sq_pessoa_pai,
                   p_restricao          => 'FORNECEDOR',
                   p_tipo_pessoa        => w_pessoa.sq_tipo_pessoa,
                   p_tipo_vinculo       => w_pessoa.sq_tipo_vinculo,
                   p_sq_pessoa          => w_chave_pessoa,
                   p_cpf                => p_cpf,
                   p_cnpj               => p_cnpj,
                   p_nome               => p_nome,
                   p_nome_resumido      => p_nome_resumido,
                   p_sexo               => p_sexo,
                   p_nascimento         => p_nascimento,
                   p_rg_numero          => p_rg_numero,
                   p_rg_emissao         => p_rg_emissao,
                   p_rg_emissor         => p_rg_emissor,
                   p_passaporte         => p_passaporte,
                   p_sq_pais_passaporte => p_sq_pais_passaporte,
                   p_inscricao_estadual => p_inscricao_estadual,
                   p_logradouro         => p_logradouro,
                   p_complemento        => p_complemento,
                   p_bairro             => p_bairro,
                   p_sq_cidade          => p_sq_cidade,
                   p_cep                => p_cep,
                   p_ddd                => p_ddd,
                   p_nr_telefone        => p_nr_telefone,
                   p_nr_fax             => p_nr_fax,
                   p_nr_celular         => p_nr_celular,
                   p_email              => p_email,
                   p_codigo_externo     => w_pessoa.codigo_externo,
                   p_chave_nova         => w_chave_pessoa);
                   
      If p_nr_conta is not null and (p_banco_estrang is not null or p_sq_agencia is not null) Then
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
      
      -- Recupera o módulo da solicitacao
      select c.sigla into w_sg_modulo
        from siw_solicitacao         a
             inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
               inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
       where a.sq_siw_solicitacao = p_chave;
       
      -- Atualiza a outra parte
      If w_sg_modulo = 'AC' Then
         update ac_acordo 
           set sq_tipo_pessoa   = w_pessoa.sq_tipo_pessoa,
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
         
         If w_forma_pagamento in ('CREDITO','DEPOSITO') Then
            update ac_acordo 
               set sq_agencia      = p_sq_agencia,
                   operacao_conta  = p_op_conta,
                   numero_conta    = p_nr_conta,
                   codigo_deposito = p_codigo_deposito
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
         
         If w_forma_pagamento in ('CREDITO','DEPOSITO') Then
            update fn_lancamento 
               set sq_agencia      = p_sq_agencia,
                   operacao_conta  = p_op_conta,
                   numero_conta    = p_nr_conta,
                   codigo_deposito = p_codigo_deposito
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
         If Nvl(p_pessoa_atual, w_chave_pessoa) <> w_chave_pessoa Then
            update pj_projeto set preposto = null where sq_siw_solicitacao = p_chave;
            delete pj_projeto_representante where sq_siw_solicitacao = p_chave;
         End If;
      End If;
         
      If p_operacao = 'I' Then
         -- Insere registro
         insert into ac_acordo_outra_parte
            (sq_acordo_outra_parte,         sq_siw_solicitacao, outra_parte,     tipo)
         values
            (sq_acordo_outra_parte.nextval, p_chave,            w_chave_pessoa,  p_tipo);
         
         select nvl(outra_parte,0) into w_existe from ac_acordo where sq_siw_solicitacao = p_chave;
         If w_existe = 0 Then
           update ac_acordo set outra_parte = w_chave_pessoa
           where sq_siw_solicitacao = p_chave;
         End If;
      Else
         If Nvl(nvl(p_pessoa_atual,0), w_chave_pessoa) <> w_chave_pessoa Then
            update ac_acordo set preposto = null where sq_siw_solicitacao = p_chave;
            
            If w_pessoa.sq_tipo_pessoa in (1,3) Then
               -- Se pessoa física, apaga dados de PJ
               delete ac_acordo_outra_rep    where sq_siw_solicitacao = p_chave;
               delete ac_acordo_outra_parte  where sq_siw_solicitacao = p_chave;
               
               insert into ac_acordo_outra_parte
                 (sq_acordo_outra_parte,         sq_siw_solicitacao, outra_parte,    tipo)
               values
                 (sq_acordo_outra_parte.nextval, p_chave,            w_chave_pessoa, p_tipo);
            End If;
         End If;
      End If;
   End If;  
end SP_PutConvOutraParte;
/
