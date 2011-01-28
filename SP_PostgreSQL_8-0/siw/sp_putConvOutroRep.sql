create or replace FUNCTION SP_PutConvOutroRep
   ( l_operacao               varchar,
     p_restricao              varchar,
     p_chave                  numeric,
     p_sq_pessoa              numeric,
     p_sq_acordo_outra_parte  numeric,
     p_cliente                numeric,
     p_cpf                    varchar,
     p_nome                   varchar,
     p_nome_resumido          varchar,
     p_sexo                   varchar,
     p_rg_numero              varchar,
     p_rg_emissao             date,
     p_rg_emissor             varchar,
     p_ddd                    varchar,
     p_nr_telefone            varchar,
     p_nr_fax                 varchar,
     p_nr_celular             varchar,
     p_email                  varchar,
     p_cargo                  varchar  
   ) RETURNS VOID AS $$
DECLARE
   
   w_sg_modulo       varchar(10);
   w_existe          numeric(18);
   w_chave_pessoa    numeric(18) := Nvl(p_sq_pessoa,0);
   w_cidade          numeric(18);
   w_tipo_fone       numeric(18);
   w_chave_fone      numeric(18);
   w_tipo_endereco   numeric(18);
   w_chave_endereco  numeric(18);
   w_sq_tipo_pessoa  numeric(18);
   w_sq_tipo_vinculo numeric(18);
BEGIN 
   If l_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM ac_acordo_outra_rep  
       where sq_pessoa             = w_chave_pessoa 
         and sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      
      select count(*) into w_existe 
        from ac_acordo_outra_rep 
       where sq_pessoa = w_chave_pessoa
         and sq_siw_solicitacao = p_chave;
      If w_existe = 0 Then 
         DELETE FROM ac_acordo_representante
          where sq_pessoa          = w_chave_pessoa 
            and sq_siw_solicitacao = p_chave;
      End If;

   Else
      -- Recupera a chave da tabela CO_TIPO_PESSOA para pessoa física
      select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa where nome = 'Física';
       
      -- Recupera a cidade padrão do cliente para definir a cidade
      select sq_cidade_padrao into w_cidade from siw_cliente where sq_pessoa = p_cliente;

      select count(*) into w_existe from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
      If w_existe > 0 Then
         select sq_pessoa into w_chave_pessoa from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
      End If;
      
      If w_chave_pessoa = 0 Then -- Se a chave da pessoa não foi informada, insere
    
         -- Verifica se o tipo do acordo e carrega a chave da tabela CO_TIPO_VINCULO
            select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Cliente' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_cliente;        
         
         -- recupera a próxima chave da pessoa
         select sq_pessoa.nextval into w_chave_pessoa from dual;
         
         -- insere os dados da pessoa
         insert into co_pessoa
           (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
         values
           (w_chave_pessoa, p_cliente,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);
    
         -- Grava dados complementares, dependendo do tipo de acordo
            update co_pessoa set cliente = 'S' where sq_pessoa = w_chave_pessoa;
         
      Else -- Caso contrário, altera
         update co_pessoa
            set nome          = p_nome,
                nome_resumido = p_nome_resumido
          where sq_pessoa = w_chave_pessoa;
    
         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
         
      End If;
       
      -- Verifica se os dados de pessoa física já existem
      select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
       
      If w_existe = 0 Then -- Se não existir insere
         insert into co_pessoa_fisica
           (sq_pessoa,      rg_numero,   rg_emissor,   rg_emissao,   cpf,   sexo,   cliente)
         values
           (w_chave_pessoa, p_rg_numero, p_rg_emissor, p_rg_emissao, p_cpf, p_sexo, p_cliente);
      Else -- Caso contrário, altera
         update co_pessoa_fisica
            set rg_numero          = Nvl(p_rg_numero, rg_numero),
                rg_emissor         = Nvl(p_rg_emissor, rg_emissor),
                rg_emissao         = Nvl(p_rg_emissao, rg_emissao),
                cpf                = Nvl(p_cpf, cpf),
                sexo               = p_sexo
          where sq_pessoa = w_chave_pessoa;
      End If;
       
      -- Verifica se a pessoa é usuária do sistema. Se não for, insere usando a lotação 
      -- do usuário de suporte. Caso contrário, atualiza o e-mail
      select count(*) into w_existe from sg_autenticacao where sq_pessoa = w_chave_pessoa;
      
      If w_existe = 0 Then
         -- Insere a pessoa na tabela de usuários do sistema, usando a lotação do usuário de suporte
         insert into sg_autenticacao
           (sq_pessoa, username, senha, assinatura, sq_unidade, sq_localizacao, cliente, email)
         (select w_chave_pessoa, 
                 p_cpf, 
                 criptografia(p_cpf), 
                 criptografia(p_cpf), 
                 a.sq_unidade,
                 a.sq_localizacao,
                 a.cliente,
                 Nvl(p_email, 'A ser informado')
            from sg_autenticacao a
           where a.cliente = p_cliente
             and a.username = '000.000.001-91'
         );

         -- Insere registros de configuração de e-mail
         insert into sg_pessoa_mail(sq_pessoa_mail, sq_pessoa, sq_menu, alerta_diario, tramitacao, conclusao, responsabilidade)
         (select sq_pessoa_mail.nextval, a.sq_pessoa, c.sq_menu, 'S', 'S', 'S', 
                 case when substr(c.sigla, 1,2) = 'PJ' then 'S' else 'N' end
            from sg_autenticacao        a 
                 inner   join co_pessoa b on (a.sq_pessoa     = b.sq_pessoa)
                   inner join siw_menu  c on (b.sq_pessoa_pai = c.sq_pessoa and c.tramite = 'S')
           where 0   = (select count(*) from sg_pessoa_mail where sq_pessoa = a.sq_pessoa and sq_menu = c.sq_menu)
             and a.sq_pessoa = w_chave_pessoa
         );
      Else
         update sg_autenticacao set email = Nvl(p_email,email) where sq_pessoa = w_chave_pessoa;
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
               w_cidade,                   'S'
              );
         Else
            update co_pessoa_endereco
               set logradouro = p_email,
                   sq_cidade  = w_cidade
             where sq_pessoa_endereco = w_chave_endereco;
         End If;
      Else
         If w_existe > 0 Then
            DELETE FROM co_pessoa_endereco where sq_pessoa_endereco = w_chave_endereco;
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
               w_cidade,                   p_ddd,          p_nr_telefone, 
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
               set sq_cidade = w_cidade,
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
               w_cidade,                   p_ddd,          p_nr_fax, 
               'S'
              );
         Else
            update co_pessoa_telefone
               set sq_cidade = w_cidade,
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
               w_cidade,                   p_ddd,          p_nr_celular, 
               'S'
              );
         Else
            update co_pessoa_telefone
               set sq_cidade = w_cidade,
                   ddd       = p_ddd,
                   numero    = p_nr_celular
             where sq_pessoa_telefone = w_chave_fone;
         End If;
      Else
         If w_existe > 0 Then
            DELETE FROM co_pessoa_telefone where sq_pessoa_telefone = w_chave_fone;
         End If;
      End If;
      If l_operacao = 'I' Then
         -- Insere registro em Representante
         select count(*) into w_existe 
           from ac_acordo_representante
          where sq_pessoa = w_chave_pessoa
            and sq_siw_solicitacao = p_chave;
         If w_existe = 0 Then
            insert into ac_acordo_representante
               (sq_pessoa, sq_siw_solicitacao)
            values
               ( w_chave_pessoa, p_chave );
         End If;
         -- Insere registro em Outra representante
         insert into ac_acordo_outra_rep
            (sq_acordo_outra_parte,        sq_pessoa, sq_siw_solicitacao, cargo)
         values
            (p_sq_acordo_outra_parte, w_chave_pessoa, p_chave, p_cargo);
      ElsIf l_operacao = 'A' Then
         update ac_acordo_outra_rep 
            set cargo = p_cargo
          where sq_acordo_outra_parte = p_sq_acordo_outra_parte
            and sq_pessoa             = w_chave_pessoa
            and sq_siw_solicitacao    = p_chave;
      End If;
   End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;