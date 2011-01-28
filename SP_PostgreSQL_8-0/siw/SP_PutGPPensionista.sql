create or replace FUNCTION SP_PutGPPensionista
   ( p_operacao            varchar,
     p_restricao           varchar,
     p_chave               numeric,
     p_chave_aux           numeric,
     p_colaborador         numeric,
     p_sq_pessoa           numeric,
     p_cpf                 varchar,
     p_nome                varchar,
     p_nome_resumido       varchar,
     p_sexo                varchar,
     p_rg_numero           varchar,
     p_rg_emissao          date,
     p_rg_emissor          varchar,
     p_ddd                 varchar,
     p_nr_telefone         varchar,
     p_nr_fax              varchar,
     p_nr_celular          varchar,
     p_sq_agencia          numeric,
     p_op_conta            varchar,
     p_nr_conta            varchar,
     p_tipo                numeric,
     p_valor               numeric,
     p_inicio              date,
     p_fim                 date,
     p_observacao          varchar  
   ) RETURNS VOID AS $$
DECLARE
   
    w_existe          numeric(4);
    w_chave_pessoa    numeric(18) := Nvl(p_sq_pessoa,0);
    w_tipo_fone       numeric(18);
    w_chave_fone      numeric(18);
    w_chave_conta     numeric(18);
    w_sq_tipo_pessoa  numeric(18);
    w_sq_tipo_vinculo numeric(18);
BEGIN
  If p_operacao is null or (p_operacao is not null and p_operacao = 'I' or p_operacao = 'A') Then
      -- Verifica se é pessoa física e carrega a chave da tabela CO_TIPO_PESSOA
      select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa   where nome = 'Física';
      select count(*)       into w_existe         from co_pessoa_fisica where cliente = p_chave_aux and cpf = p_cpf;
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
        values
          (w_chave_pessoa, p_chave_aux,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);
     Else -- Caso contrário, altera
        update co_pessoa
           set nome          = Nvl(p_nome, nome),
               nome_resumido = Nvl(p_nome_resumido, nome_resumido)
         where sq_pessoa = w_chave_pessoa;
     End If;
     
     If p_cpf is not null then -- Se for pessoa física
        -- Verifica se os dados de pessoa física já existem
        select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
        
        If w_existe = 0 Then -- Se não existir insere
           insert into co_pessoa_fisica
             (sq_pessoa,         nascimento,        rg_numero,            rg_emissor,   rg_emissao,   
              cpf,               passaporte_numero, sq_pais_passaporte,   sexo,         cliente
             )
           values
             (w_chave_pessoa,    null,      p_rg_numero,          p_rg_emissor, p_rg_emissao, 
              p_cpf,             null,      null, p_sexo,       p_chave_aux
             );
        Else -- Caso contrário, altera
           update co_pessoa_fisica
              set rg_numero          = p_rg_numero,
                  rg_emissor         = p_rg_emissor,
                  rg_emissao         = p_rg_emissao,
                  cpf                = p_cpf,
                  sexo               = p_sexo
            where sq_pessoa = w_chave_pessoa;
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
           (select sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone, 
                   a.sq_cidade_padrao,         p_ddd,          p_nr_telefone, 
                   'S'
              from siw_cliente a
             where a.sq_pessoa = p_chave_aux 
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
              set ddd       = p_ddd,
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
           (select sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone, 
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
           (select sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone, 
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
           DELETE FROM co_pessoa_telefone where sq_pessoa_telefone = w_chave_fone;
        End If;
     End If;

     If p_sq_agencia is not null and p_nr_conta is not null Then
        -- Se foi informado o banco, grava
        select count(*) into w_existe
          from co_pessoa_conta a
         where a.sq_pessoa  = w_chave_pessoa
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
              and a.ativo      = 'S'
              and a.padrao     = 'S';
              
           update co_pessoa_conta
              set sq_agencia = p_sq_agencia,
                  operacao   = p_op_conta,
                  numero     = p_nr_conta
            where sq_pessoa_conta = w_chave_conta;
        End If;

        -- Se foram informados os dados do pensionista, grava
         select count(*) into w_existe
           from gp_pensao a
          where a.sq_pessoa = w_chave_pessoa;
         If w_existe = 0 Then
           insert into gp_pensao
            (sq_pessoa, cliente, colaborador, tipo, valor, inicio, fim, observacao)
           values
             (w_chave_pessoa,
              p_chave_aux,
              p_colaborador,
              p_tipo,
              p_valor,
              p_inicio,
              p_fim,
              p_observacao);
         Else
           update gp_pensao
           set sq_pessoa = w_chave_pessoa,
               cliente = p_chave_aux,
               colaborador = p_colaborador,
               tipo = p_tipo,
               valor = p_valor,
               inicio = p_inicio,
               fim = p_fim,
               observacao = p_observacao
         where sq_pessoa = w_chave_pessoa;
         End If;
     End If;
   Elsif p_operacao = 'E' and p_chave is not null then
     DELETE FROM gp_pensao
      where sq_pessoa = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;