create or replace procedure SP_PutConvOutraParte
   ( p_operacao                     in varchar2,
     p_restricao                    in varchar2,
     p_sq_acordo_outra_parte        in number    default null,
     p_chave                        in number    default null,
     p_sq_pessoa                    in number    default null,
     p_tipo                         in number    default null,
     p_chave_aux                    in number    default null,
     p_cnpj                         in varchar2  default null,
     p_nome                         in varchar2  default null,
     p_nome_resumido                in varchar2  default null,
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
   w_tipo_fone          number(18);
   w_chave_fone         number(18);
   w_tipo_endereco      number(18);
   w_chave_endereco     number(18);
   w_chave_conta        number(18);
   w_sq_tipo_pessoa     number(18);
   w_sq_tipo_vinculo    number(18);
   w_sq_siw_solicitacao number(18);
   w_outra_parte        number(18);
begin
   If p_operacao = 'E' Then 
      -- Exclui registro
      select sq_siw_solicitacao, outra_parte into w_sq_siw_solicitacao, w_outra_parte
        from ac_acordo_outra_parte 
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      
      delete ac_acordo_outra_parte  where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      update ac_acordo set outra_parte = null         
       where sq_siw_solicitacao = w_sq_siw_solicitacao 
         and outra_parte        = w_outra_parte;
  Else
     -- Verifica se é pessoa física ou jurídica e carrega a chave da tabela CO_TIPO_PESSOA
      If p_cnpj is not null Then 
         select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa     where nome = 'Jurídica';
         select count(*)       into w_existe         from co_pessoa_juridica where cliente = p_chave_aux and cnpj = p_cnpj;
         If w_existe > 0 Then
            select sq_pessoa into w_chave_pessoa from co_pessoa_juridica where cliente = p_chave_aux and cnpj = p_cnpj;
         Else
            w_chave_pessoa := 0;
         End If;
      End If;
      
      If w_chave_pessoa = 0 Then -- Se a chave da pessoa não foi informada, insere
   
         -- Carrega a chave da tabela CO_TIPO_VINCULO, dependendo do tipo da solicitação
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Cliente' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_chave_aux;
         
         -- recupera a próxima chave da pessoa
         select sq_pessoa.nextval into w_chave_pessoa from dual;
         
         -- insere os dados da pessoa
         insert into co_pessoa
           (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
         values
           (w_chave_pessoa, p_chave_aux,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);
   
         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
         
      Else -- Caso contrário, altera
         update co_pessoa
            set nome          = Nvl(p_nome, nome),
                nome_resumido = Nvl(p_nome_resumido, nome_resumido)
          where sq_pessoa = w_chave_pessoa;
   
         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
         
      End If;
      
      If p_cnpj is not null then
         -- Verifica se os dados de pessoa jurídica já existem
         select count(*) into w_existe from co_pessoa_juridica where sq_pessoa = w_chave_pessoa;
         
         If w_existe = 0 Then -- Se não existir insere
            insert into co_pessoa_juridica
              (sq_pessoa,      cnpj,   inscricao_estadual,   cliente)
            values
              (w_chave_pessoa, p_cnpj, p_inscricao_estadual, p_chave_aux);
         Else -- Caso contrário, altera
            update co_pessoa_juridica
               set cnpj               = p_cnpj,
                   inscricao_estadual = Nvl(p_inscricao_estadual, inscricao_estadual)
             where sq_pessoa = w_chave_pessoa;
         End If;
      End If;
      
      -- Se foi informado o e-mail, grava. Caso contrário, remove.
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
               complemento,                bairro,         sq_cidade,        cep,    padrao
              )
            values
              (sq_pessoa_endereco.nextval, w_chave_pessoa, w_tipo_endereco,  p_logradouro, 
               p_complemento,              p_bairro,       p_sq_cidade,      p_cep,  'S'
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
   
      -- Se foi informado o fax, grava. Caso contrário remove.
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
   
      -- Se foi informado o celular, grava. Caso contrário, remove.
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
      
      -- Insere registro
      insert into ac_acordo_outra_parte
         (sq_acordo_outra_parte        , sq_siw_solicitacao,    outra_parte,   tipo)
      values
         (sq_acordo_outra_parte.nextval, p_chave           , w_chave_pessoa,  p_tipo);
      select nvl(outra_parte,0) into w_existe from ac_acordo where sq_siw_solicitacao = p_chave;
      If w_existe = 0 and p_tipo = 1 Then
        update ac_acordo set outra_parte = w_chave_pessoa
        where sq_siw_solicitacao = p_chave;
      End If;
   End If;  
end SP_PutConvOutraParte;
/
create or replace procedure SP_PutConvOutraParte
   ( p_operacao                     in varchar2,
     p_restricao                    in varchar2,
     p_sq_acordo_outra_parte        in number    default null,
     p_chave                        in number    default null,
     p_sq_pessoa                    in number    default null,
     p_tipo                         in number    default null,
     p_chave_aux                    in number    default null,
     p_cnpj                         in varchar2  default null,
     p_nome                         in varchar2  default null,
     p_nome_resumido                in varchar2  default null,
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
   w_tipo_fone          number(18);
   w_chave_fone         number(18);
   w_tipo_endereco      number(18);
   w_chave_endereco     number(18);
   w_chave_conta        number(18);
   w_sq_tipo_pessoa     number(18);
   w_sq_tipo_vinculo    number(18);
   w_sq_siw_solicitacao number(18);
   w_outra_parte        number(18);
begin
   If p_operacao = 'E' Then 
      -- Exclui registro
      select sq_siw_solicitacao, outra_parte into w_sq_siw_solicitacao, w_outra_parte
        from ac_acordo_outra_parte 
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      
      delete ac_acordo_outra_parte  where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      update ac_acordo set outra_parte = null         
       where sq_siw_solicitacao = w_sq_siw_solicitacao 
         and outra_parte        = w_outra_parte;
  Else
     -- Verifica se é pessoa física ou jurídica e carrega a chave da tabela CO_TIPO_PESSOA
      If p_cnpj is not null Then 
         select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa     where nome = 'Jurídica';
         select count(*)       into w_existe         from co_pessoa_juridica where cliente = p_chave_aux and cnpj = p_cnpj;
         If w_existe > 0 Then
            select sq_pessoa into w_chave_pessoa from co_pessoa_juridica where cliente = p_chave_aux and cnpj = p_cnpj;
         Else
            w_chave_pessoa := 0;
         End If;
      End If;
      
      If w_chave_pessoa = 0 Then -- Se a chave da pessoa não foi informada, insere
   
         -- Carrega a chave da tabela CO_TIPO_VINCULO, dependendo do tipo da solicitação
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Cliente' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_chave_aux;
         
         -- recupera a próxima chave da pessoa
         select sq_pessoa.nextval into w_chave_pessoa from dual;
         
         -- insere os dados da pessoa
         insert into co_pessoa
           (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
         values
           (w_chave_pessoa, p_chave_aux,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);
   
         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
         
      Else -- Caso contrário, altera
         update co_pessoa
            set nome          = Nvl(p_nome, nome),
                nome_resumido = Nvl(p_nome_resumido, nome_resumido)
          where sq_pessoa = w_chave_pessoa;
   
         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
         
      End If;
      
      If p_cnpj is not null then
         -- Verifica se os dados de pessoa jurídica já existem
         select count(*) into w_existe from co_pessoa_juridica where sq_pessoa = w_chave_pessoa;
         
         If w_existe = 0 Then -- Se não existir insere
            insert into co_pessoa_juridica
              (sq_pessoa,      cnpj,   inscricao_estadual,   cliente)
            values
              (w_chave_pessoa, p_cnpj, p_inscricao_estadual, p_chave_aux);
         Else -- Caso contrário, altera
            update co_pessoa_juridica
               set cnpj               = p_cnpj,
                   inscricao_estadual = Nvl(p_inscricao_estadual, inscricao_estadual)
             where sq_pessoa = w_chave_pessoa;
         End If;
      End If;
      
      -- Se foi informado o e-mail, grava. Caso contrário, remove.
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
               complemento,                bairro,         sq_cidade,        cep,    padrao
              )
            values
              (sq_pessoa_endereco.nextval, w_chave_pessoa, w_tipo_endereco,  p_logradouro, 
               p_complemento,              p_bairro,       p_sq_cidade,      p_cep,  'S'
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
   
      -- Se foi informado o fax, grava. Caso contrário remove.
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
   
      -- Se foi informado o celular, grava. Caso contrário, remove.
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
      
      -- Insere registro
      insert into ac_acordo_outra_parte
         (sq_acordo_outra_parte        , sq_siw_solicitacao,    outra_parte,   tipo)
      values
         (sq_acordo_outra_parte.nextval, p_chave           , w_chave_pessoa,  p_tipo);
      select nvl(outra_parte,0) into w_existe from ac_acordo where sq_siw_solicitacao = p_chave;
      If w_existe = 0 and p_tipo = 1 Then
        update ac_acordo set outra_parte = w_chave_pessoa
        where sq_siw_solicitacao = p_chave;
      End If;
   End If;  
end SP_PutConvOutraParte;
/
create or replace procedure SP_PutConvOutraParte
   ( p_operacao                     in varchar2,
     p_restricao                    in varchar2,
     p_sq_acordo_outra_parte        in number    default null,
     p_chave                        in number    default null,
     p_sq_pessoa                    in number    default null,
     p_tipo                         in number    default null,
     p_chave_aux                    in number    default null,
     p_cnpj                         in varchar2  default null,
     p_nome                         in varchar2  default null,
     p_nome_resumido                in varchar2  default null,
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
   w_tipo_fone          number(18);
   w_chave_fone         number(18);
   w_tipo_endereco      number(18);
   w_chave_endereco     number(18);
   w_chave_conta        number(18);
   w_sq_tipo_pessoa     number(18);
   w_sq_tipo_vinculo    number(18);
   w_sq_siw_solicitacao number(18);
   w_outra_parte        number(18);
begin
   If p_operacao = 'E' Then 
      -- Exclui registro
      select sq_siw_solicitacao, outra_parte into w_sq_siw_solicitacao, w_outra_parte
        from ac_acordo_outra_parte 
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      
      delete ac_acordo_outra_parte  where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      update ac_acordo set outra_parte = null         
       where sq_siw_solicitacao = w_sq_siw_solicitacao 
         and outra_parte        = w_outra_parte;
  Else
     -- Verifica se é pessoa física ou jurídica e carrega a chave da tabela CO_TIPO_PESSOA
      If p_cnpj is not null Then 
         select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa     where nome = 'Jurídica';
         select count(*)       into w_existe         from co_pessoa_juridica where cliente = p_chave_aux and cnpj = p_cnpj;
         If w_existe > 0 Then
            select sq_pessoa into w_chave_pessoa from co_pessoa_juridica where cliente = p_chave_aux and cnpj = p_cnpj;
         Else
            w_chave_pessoa := 0;
         End If;
      End If;
      
      If w_chave_pessoa = 0 Then -- Se a chave da pessoa não foi informada, insere
   
         -- Carrega a chave da tabela CO_TIPO_VINCULO, dependendo do tipo da solicitação
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Cliente' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_chave_aux;
         
         -- recupera a próxima chave da pessoa
         select sq_pessoa.nextval into w_chave_pessoa from dual;
         
         -- insere os dados da pessoa
         insert into co_pessoa
           (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
         values
           (w_chave_pessoa, p_chave_aux,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);
   
         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
         
      Else -- Caso contrário, altera
         update co_pessoa
            set nome          = Nvl(p_nome, nome),
                nome_resumido = Nvl(p_nome_resumido, nome_resumido)
          where sq_pessoa = w_chave_pessoa;
   
         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
         
      End If;
      
      If p_cnpj is not null then
         -- Verifica se os dados de pessoa jurídica já existem
         select count(*) into w_existe from co_pessoa_juridica where sq_pessoa = w_chave_pessoa;
         
         If w_existe = 0 Then -- Se não existir insere
            insert into co_pessoa_juridica
              (sq_pessoa,      cnpj,   inscricao_estadual,   cliente)
            values
              (w_chave_pessoa, p_cnpj, p_inscricao_estadual, p_chave_aux);
         Else -- Caso contrário, altera
            update co_pessoa_juridica
               set cnpj               = p_cnpj,
                   inscricao_estadual = Nvl(p_inscricao_estadual, inscricao_estadual)
             where sq_pessoa = w_chave_pessoa;
         End If;
      End If;
      
      -- Se foi informado o e-mail, grava. Caso contrário, remove.
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
               complemento,                bairro,         sq_cidade,        cep,    padrao
              )
            values
              (sq_pessoa_endereco.nextval, w_chave_pessoa, w_tipo_endereco,  p_logradouro, 
               p_complemento,              p_bairro,       p_sq_cidade,      p_cep,  'S'
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
   
      -- Se foi informado o fax, grava. Caso contrário remove.
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
   
      -- Se foi informado o celular, grava. Caso contrário, remove.
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
      
      -- Insere registro
      insert into ac_acordo_outra_parte
         (sq_acordo_outra_parte        , sq_siw_solicitacao,    outra_parte,   tipo)
      values
         (sq_acordo_outra_parte.nextval, p_chave           , w_chave_pessoa,  p_tipo);
      select nvl(outra_parte,0) into w_existe from ac_acordo where sq_siw_solicitacao = p_chave;
      If w_existe = 0 and p_tipo = 1 Then
        update ac_acordo set outra_parte = w_chave_pessoa
        where sq_siw_solicitacao = p_chave;
      End If;
   End If;  
end SP_PutConvOutraParte;
/
