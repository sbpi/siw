create or replace procedure SP_PutPD_Fatura_Outros
   (p_operacao            in  varchar2,
    p_cliente             in  number,
    p_chave               in  number    default null,
    p_solic               in  number    default null,
    p_fatura              in  number    default null,
    p_tipo                in  number    default null,
    p_cnpj                in  varchar2  default null,
    p_nome                in  varchar2  default null,
    p_inicio              in  date      default null,
    p_fim                 in  date      default null,
    p_valor               in  number    default null
   ) is
   w_existe number(18);
   w_pessoa number(18);
begin
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
        select sq_pessoa.nextval into w_pessoa from dual;
        
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
   End If;
end SP_PutPD_Fatura_Outros;
/
