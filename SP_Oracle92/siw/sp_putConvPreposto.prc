create or replace procedure SP_PutConvPreposto
   ( l_operacao               in varchar2,
     p_restricao              in varchar2,
     p_chave                  in number    default null,
     p_sq_acordo_outra_parte  in number    default null,
     p_sq_pessoa              in number    default null,
     p_cliente                in number    default null,
     p_cpf                    in varchar2  default null,
     p_nome                   in varchar2  default null,
     p_nome_resumido          in varchar2  default null,
     p_sexo                   in varchar2  default null,
     p_rg_numero              in varchar2  default null,
     p_rg_emissao             in date      default null,
     p_rg_emissor             in varchar2  default null,
     p_ddd                    in varchar2  default null,
     p_nr_telefone            in varchar2  default null,
     p_nr_fax                 in varchar2  default null,
     p_nr_celular             in varchar2  default null,
     p_email                  in varchar2  default null,
     p_cargo                  in varchar2  default null
   ) is
   
   w_sg_modulo       varchar2(10);
   w_existe          number(18);
   w_chave_pessoa    number(18) := Nvl(p_sq_pessoa,0);
   w_sq_tipo_pessoa  number(18);
   w_sq_tipo_vinculo number(18);
   w_outra_parte1    number(18);
   w_outra_parte2    number(18);
   w_chave           number(18);
   w_preposto        number(18);
   w_cidade          number(18);
   w_tipo_fone       number(18);
   w_chave_fone      number(18);
   w_tipo_endereco   number(18);
   w_chave_endereco  number(18);   
begin
   -- Carrega a chave da tabela CO_TIPO_PESSOA
   select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa where nome = 'Física';
   
   select count(*) into w_existe from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
   If w_existe > 0 Then
      select sq_pessoa into w_chave_pessoa from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
   End If;

   If l_operacao = 'I' Then
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
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
          
         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
      End If;
      -- Verifica se os dados de pessoa física já existem
      select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
      
      If w_existe = 0 Then -- Se não existir insere
         insert into co_pessoa_fisica
           (sq_pessoa,      cpf,   sexo,   rg_numero,   rg_emissor, rg_emissao,     cliente)
         values
           (w_chave_pessoa, p_cpf, p_sexo, p_rg_numero, p_rg_emissor, p_rg_emissao, p_cliente);
      End If;
      
      -- Insere registro
      insert into ac_acordo_preposto
         (sq_siw_solicitacao, sq_acordo_outra_parte  , sq_pessoa, cargo)
      values
         (p_chave           , p_sq_acordo_outra_parte, w_chave_pessoa, p_cargo);
      
      select nvl(preposto,0) into w_existe from ac_acordo where sq_siw_solicitacao = p_chave;
      If w_existe = 0 Then
         select outra_parte into w_outra_parte1 from ac_acordo where sq_siw_solicitacao = p_chave;
         select outra_parte into w_outra_parte2 from ac_acordo_outra_parte where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
         If w_outra_parte1 = w_outra_parte2 Then
           update ac_acordo set preposto = w_chave_pessoa where sq_siw_solicitacao = p_chave;
         End If;
      End If;      
   Elsif  l_operacao = 'A' Then 
   -- Caso contrário, altera
         update co_pessoa
            set nome          = p_nome,
                nome_resumido = p_nome_resumido
          where sq_pessoa = w_chave_pessoa;
   -- Caso contrário, altera
         update co_pessoa_fisica
            set cpf                = p_cpf,
                sexo               = p_sexo,
                rg_numero          = p_rg_numero,
                rg_emissor         = p_rg_emissor,
                rg_emissao         = p_rg_emissao
          where sq_pessoa = w_chave_pessoa;
          update ac_acordo_preposto
             set cargo = p_cargo
         where sq_pessoa             = w_chave_pessoa
           and sq_acordo_outra_parte = p_sq_acordo_outra_parte
           and sq_siw_solicitacao    = p_chave;
      
   Elsif l_operacao = 'E' Then
      -- Exclui registro
      select sq_siw_solicitacao into w_chave 
        from ac_acordo_outra_parte 
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
       
      select count(*) into w_existe from ac_acordo_preposto where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      
      delete ac_acordo_preposto  
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte
         and sq_pessoa             = w_chave_pessoa;             
      
      If w_existe > 1 Then
        select sq_pessoa into w_preposto 
          from ac_acordo_preposto 
         where sq_acordo_outra_parte = p_sq_acordo_outra_parte
           and rownum = 1;
         
        update ac_acordo set preposto = w_preposto
         where sq_siw_solicitacao = w_chave
           and preposto           = w_chave_pessoa;
      Else
        update ac_acordo set preposto = null         
         where sq_siw_solicitacao = w_chave
           and preposto           = w_chave_pessoa;
      End If;      
   End If;
   If l_operacao = 'I' or l_operacao = 'A' Then
      -- Recupera a cidade padrão do cliente para definir a cidade
      select sq_cidade_padrao into w_cidade from siw_cliente where sq_pessoa = p_cliente;
   
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
            delete co_pessoa_endereco where sq_pessoa_endereco = w_chave_endereco;
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
            delete co_pessoa_telefone where sq_pessoa_telefone = w_chave_fone;
         End If;
      End If;   
   End If;      
end SP_PutConvPreposto;
/
