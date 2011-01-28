create or replace FUNCTION SP_PutGPModalidade
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   varchar,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_sigla                     varchar,
    p_ferias                    varchar,
    p_username                  varchar,
    p_passagem                  varchar,
    p_diaria                    varchar,
    p_horas_extras              varchar,
    p_ativo                     varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_modalidade_contrato
        (sq_modalidade_contrato, cliente, nome, descricao, sigla, ferias, username, passagem, diaria, horas_extras, ativo)
      values
        (sq_modalidade_contrato.nextval, p_cliente, trim(p_nome), p_descricao, upper(trim(p_sigla)), p_ferias, p_username, p_passagem, p_diaria, p_horas_extras, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gp_modalidade_contrato
         set cliente       = p_cliente,
             nome          = trim(p_nome),
             descricao     = p_descricao,
             sigla         = upper(trim(p_sigla)),
             ferias        = p_ferias,
             username      = p_username,
             passagem      = p_passagem,
             diaria        = p_diaria,
             horas_extras  = p_horas_extras,
             ativo         = p_ativo
       where sq_modalidade_contrato = p_chave;
      If p_ativo = 'N' Then
         DELETE FROM gp_afastamento_modalidade where sq_modalidade_contrato = p_chave;
      End If;
   Elsif p_operacao = 'E' Then
      -- Exclui os registro de ligação com os tipos de afastamento
      DELETE FROM gp_afastamento_modalidade where sq_modalidade_contrato = p_chave;
      -- Exclui registro
      DELETE FROM gp_modalidade_contrato where sq_modalidade_contrato = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;