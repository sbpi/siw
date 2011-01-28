create or replace FUNCTION SP_PutCTEspecificacao
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_chave                     numeric,
    p_chave_pai                 numeric,
    p_sq_cc                     numeric,
    p_ano                       varchar,
    p_codigo                    varchar,
    p_nome                      varchar,
    p_valor                     numeric,
    p_ultimo_nivel              varchar,
    p_ativo                     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ct_especificacao_despesa
        (sq_especificacao_despesa, cliente, sq_cc, especificacao_pai, 
         ano, codigo, nome, valor, ultimo_nivel, ativo)
        (select sq_especificacao_despesa.nextval,  p_cliente,  p_sq_cc,  p_chave_pai, 
                 p_ano,  p_codigo,  p_nome,  p_valor,  p_ultimo_nivel,  p_ativo 
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ct_especificacao_despesa
         set sq_cc             = p_sq_cc,
             especificacao_pai = p_chave_pai,
             ano               = p_ano,
             codigo            = p_codigo,
             nome              = p_nome,
             valor             = p_valor,
             ultimo_nivel      = p_ultimo_nivel,
             ativo             = p_ativo
       where sq_especificacao_despesa = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM ct_especificacao_despesa where sq_especificacao_despesa = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa o registro
      update ct_especificacao_despesa set ativo = 'S' where sq_especificacao_despesa = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa o registro
      update ct_especificacao_despesa set ativo = 'N' where sq_especificacao_despesa = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;