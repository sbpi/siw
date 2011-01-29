create or replace FUNCTION sp_putGPFamiliares 
 (p_operacao            varchar,
  p_chave               numeric,
  p_cliente             numeric,
  p_colaborador         numeric,
  p_cpf                 varchar,
  p_nome                varchar,
  p_nome_resumido       varchar,
  p_nascimento          date,
  p_sexo                varchar,
  p_parentesco          numeric,
  p_seguro_vida         varchar,
  p_seguro_saude        varchar,
  p_seguro_odonto       varchar,
  p_imposto_renda       varchar
  ) RETURNS VOID AS $$
DECLARE

  w_existe          numeric(4);
  w_chave_pessoa    numeric(18) := Nvl(p_chave,0);
  w_sq_tipo_pessoa  numeric(18);
  w_sq_tipo_vinculo numeric(18);

BEGIN   
  If p_operacao is null or (p_operacao is not null and p_operacao = 'I' or p_operacao = 'A') Then
        -- Verifica se é pessoa física e carrega a chave da tabela CO_TIPO_PESSOA    
    If p_cpf is not null Then 
      select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa   where nome = 'Física';
      select count(*)       into w_existe         from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
      If w_existe > 0 Then
         select sq_pessoa into w_chave_pessoa from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
      End If;   
    Else
        select count(*)
          into w_existe
          from sg_autenticacao a
               inner join co_pessoa b on (a.sq_pessoa = b.sq_pessoa)                
         where b.sq_pessoa_pai = p_cliente
--           and (p_chave is null or p_chave is not null and a.sq_pessoa = p_chave)
           and (p_chave is not null and a.sq_pessoa = p_chave)
           and (p_cpf is null or p_cpf is not null and username = p_cpf);
        If w_existe > 0 Then
           select a.sq_pessoa
             into w_chave_pessoa
             from sg_autenticacao a
                  inner join co_pessoa b on (a.sq_pessoa = b.sq_pessoa)                
            where b.sq_pessoa_pai = p_cliente
              and (p_chave is null or p_chave is not null and a.sq_pessoa = p_chave)
              and (p_cpf is null or p_cpf is not null and username = p_cpf);
        Else
           w_chave_pessoa := Nvl(p_chave,0);
        End If;
      End If;
   --End If;
     
   If w_chave_pessoa = 0 Then -- Se a chave da pessoa não foi informada, insere

        -- recupera a próxima chave da pessoa
        select nextVal('sq_pessoa') into w_chave_pessoa;
        
        -- insere os dados da pessoa
        insert into co_pessoa
          (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
        values
          (w_chave_pessoa, p_cliente,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);
     Else -- Caso contrário, altera
        update co_pessoa
           set nome          = Nvl(p_nome, nome),
               nome_resumido = Nvl(p_nome_resumido, nome_resumido)
         where sq_pessoa = w_chave_pessoa;
     End If;
     
     If p_cpf is not null then -- Se for pessoa física
       select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
                   
       If w_existe = 0 Then -- Se não existir insere
          insert into co_pessoa_fisica
            (sq_pessoa,nascimento,cpf,sexo,cliente)
          values
            (w_chave_pessoa,p_nascimento,p_cpf,p_sexo,p_cliente);
       Else -- Caso contrário, altera
          update co_pessoa_fisica
             set cpf                = p_cpf,
                 sexo               = p_sexo
           where sq_pessoa = w_chave_pessoa;
       End If;
     Else
       select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
                   
       If w_existe = 0 Then -- Se não existir insere
          insert into co_pessoa_fisica
            (sq_pessoa,nascimento,cpf,sexo,cliente)
          values
            (w_chave_pessoa,p_nascimento,p_cpf,p_sexo,p_cliente);
       Else -- Caso contrário, altera
          update co_pessoa_fisica
             set cpf                = p_cpf,
                 sexo               = p_sexo
           where sq_pessoa = w_chave_pessoa;
       End If;       
     End If;


     -- Se foram informados os dados do familiar, grava
    select count(*)
      into w_existe
      from gp_pessoa_vinculo a
     where a.sq_pessoa = w_chave_pessoa;
    If w_existe = 0 Then
      -- Insere o registro
      insert into gp_pessoa_vinculo
        (sq_pessoa, cliente, colaborador, tipo, seguro_vida, seguro_saude, seguro_odonto, imposto_renda)
      values
        (w_chave_pessoa, p_cliente, p_colaborador, p_parentesco, p_seguro_vida, p_seguro_saude, p_seguro_odonto, p_imposto_renda);
    Else
    -- Altera o registro
      update gp_pessoa_vinculo
         set tipo          = p_parentesco,
             seguro_vida   = p_seguro_vida,
             seguro_saude  = p_seguro_saude,
             seguro_odonto = p_seguro_odonto,
             imposto_renda = p_imposto_renda
       where sq_pessoa = p_chave;
    End If;
  Elsif p_operacao = 'E' Then     
    DELETE FROM gp_pessoa_vinculo
     where sq_pessoa = p_chave;
  End If;
  END; $$ LANGUAGE 'PLPGSQL' VOLATILE;