create or replace FUNCTION SP_PutLcModalidade
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_nome                      varchar,
    p_sigla                     varchar,    
    p_descricao                 varchar,
    p_fundamentacao             varchar,
    p_minimo_pesquisas          numeric,
    p_minimo_participantes      numeric,
    p_minimo_propostas_validas  numeric,
    p_certame                   varchar,
    p_enquadramento_inicial     numeric,
    p_enquadramento_final       numeric,
    p_contrato                  varchar,
    p_ativo                     varchar,
    p_padrao                    varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_modalidade(
              sq_lcmodalidade,              cliente,                 nome,                       sigla,                      descricao,   
              fundamentacao,                minimo_pesquisas,        minimo_participantes,       minimo_propostas_validas,   certame,
              enquadramento_inicial,        enquadramento_final,     ativo,                      padrao,                     gera_contrato
             )
      (select sq_lcmodalidade.nextval,      p_cliente,               p_nome,                     p_sigla,                    p_descricao, 
              p_fundamentacao,              p_minimo_pesquisas,      p_minimo_participantes,     p_minimo_propostas_validas, p_certame,
              p_enquadramento_inicial,      p_enquadramento_final,   p_ativo,                    p_padrao,                   p_contrato
        
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_modalidade set
         nome                          = p_nome,
         sigla                         = p_sigla,
         descricao                     = p_descricao,
         fundamentacao                 = p_fundamentacao,
         ativo                         = p_ativo,
         padrao                        = p_padrao,
         minimo_pesquisas              = p_minimo_pesquisas,
         minimo_participantes          = p_minimo_participantes,
         minimo_propostas_validas      = p_minimo_propostas_validas,
         certame                       = p_certame,
         enquadramento_inicial         = p_enquadramento_inicial,
         enquadramento_final           = p_enquadramento_final,
         gera_contrato                 = p_contrato
       where sq_lcmodalidade           = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM lc_modalidade where sq_lcmodalidade = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;