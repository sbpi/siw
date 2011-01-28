create or replace FUNCTION SP_PutGPColaborador
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_sq_pessoa                 numeric,
    p_ctps_numero               varchar,
    p_ctps_serie                varchar,
    p_ctps_emissor              varchar,    
    p_ctps_emissao              date,
    p_pis_pasep                 varchar,
    p_pispasep_numero           varchar,
    p_pispasep_cadastr          date,
    p_te_numero                 varchar,
    p_te_zona                   varchar,
    p_te_secao                  varchar,
    p_reservista_numero         varchar,
    p_reservista_csm            varchar,
    p_tipo_sangue               varchar,
    p_doador_sangue             varchar,
    p_doador_orgaos             varchar,
    p_observacoes               varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_colaborador (sq_pessoa, cliente, ctps_numero, ctps_serie, ctps_emissor, ctps_emissao_data,
                                  pis_pasep, pispasep_numero, pispasep_cadastr, te_numero, te_zona, te_secao,
                                  reservista_numero, reservista_csm, tipo_sangue, doador_sangue, doador_orgaos, 
                                  observacoes) 
      values (p_sq_pessoa, p_cliente, p_ctps_numero, p_ctps_serie, p_ctps_emissor, p_ctps_emissao,
              p_pis_pasep, p_pispasep_numero, p_pispasep_cadastr, p_te_numero, p_te_zona, p_te_secao, 
              p_reservista_numero, p_reservista_csm, p_tipo_sangue, p_doador_sangue, p_doador_orgaos,
              p_observacoes);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gp_colaborador
         set ctps_numero            = p_ctps_numero,
             ctps_serie             = p_ctps_serie,
             ctps_emissor           = p_ctps_emissor,
             ctps_emissao_data      = p_ctps_emissao,
             pis_pasep              = p_pis_pasep,
             pispasep_numero        = p_pispasep_numero,
             pispasep_cadastr       = p_pispasep_cadastr,
             te_numero              = p_te_numero,
             te_zona                = p_te_zona,
             te_secao               = p_te_secao,
             reservista_numero      = p_reservista_numero,
             reservista_csm         = p_reservista_csm,
             tipo_sangue            = p_tipo_sangue,
             doador_sangue          = p_doador_sangue,
             doador_orgaos          = p_doador_orgaos,
             observacoes            = p_observacoes
       where sq_pessoa = p_sq_pessoa;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM gp_contrato_colaborador where sq_pessoa = p_sq_pessoa;
      DELETE FROM gp_colaborador where sq_pessoa = p_sq_pessoa;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;