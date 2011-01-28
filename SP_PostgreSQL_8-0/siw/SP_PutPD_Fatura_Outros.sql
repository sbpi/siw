create or replace FUNCTION SP_PutPD_Fatura_Outros
   (p_operacao             varchar,
    p_cliente              numeric,
    p_chave                numeric,
    p_solic                numeric,
    p_fatura               numeric,
    p_tipo                 numeric,
    p_cnpj                 varchar,
    p_nome                 varchar,
    p_inicio               date,
    p_fim                  date,
    p_valor                numeric    
   ) RETURNS VOID AS $$
DECLARE
   w_existe numeric(18);
   w_pessoa numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Verifica se a pessoa existe em CO_PESSOA
      select count(*) into w_existe 
        from co_pessoa_juridica   a 
             inner join co_pessoa b on (a.sq_pessoa     = b.sq_pessoa and 
                                        b.sq_pessoa_pai = p_cliente
                                       )
       where cnpj = p_cnpj;

      If w_existe = 0 Then
        -- Recupera a próxima chave de CO_PESSOA
        select sq_pessoa.nextval into w_pessoa;
        
        -- Insere em CO_PESSOA
        insert into co_pessoa
          (sq_pessoa, sq_pessoa_pai, sq_tipo_vinculo, sq_tipo_pessoa, nome,   nome_resumido)
        values
          (w_pessoa,  p_cliente,     null,            2,              p_nome, substr(p_nome,1,20));

        -- Insere em CO_PESSOA_JURIDICA
        insert into co_pessoa_juridica (sq_pessoa, cnpj, cliente) values (w_pessoa, p_cnpj, p_cliente);
        
      Else
        -- Recupera a chave da pessoa
        select a.sq_pessoa into w_pessoa 
          from co_pessoa_juridica   a 
               inner join co_pessoa b on (a.sq_pessoa     = b.sq_pessoa and 
                                          b.sq_pessoa_pai = p_cliente
                                         )
         where cnpj = p_cnpj;

        -- Atualiza o nome e o nome resumido
        update co_pessoa
           set nome          = p_nome,
               nome_resumido = substr(p_nome,1,20)
         where sq_pessoa = w_pessoa;
      End If;

      -- Insere registro na tabela de fatura de hospedagem/locação/veículo
      insert into pd_fatura_outros
        (sq_fatura_outros,         sq_siw_solicitacao, sq_fatura_agencia, sq_pessoa, tipo,   inicio,   fim,   valor)
      values
        (sq_fatura_outros.nextval, p_solic,            p_fatura,          w_pessoa,  p_tipo, p_inicio, p_fim, p_valor);
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;