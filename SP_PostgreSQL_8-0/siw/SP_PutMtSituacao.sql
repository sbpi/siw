create or replace FUNCTION SP_PutMtSituacao
   (p_operacao                  varchar,    
    p_cliente                   numeric,    
    p_chave                     numeric,    
    p_nome                      varchar,
    p_sigla                     varchar,
    p_entrada                   varchar,
    p_saida                     varchar,
    p_estorno                   varchar,
    p_consumo                   varchar,
    p_permanente                varchar,
    p_inativa_bem               varchar,
    p_situacao_fisica           varchar,
    p_ativo                     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into mt_situacao (
                  sq_mtsituacao, cliente, nome, sigla, entrada, saida, 
                  estorno, consumo, permanente, inativa_bem,situacao_fisica, ativo)
      (select sq_mtsituacao.nextval, p_cliente, trim(p_nome), trim(p_sigla), trim(p_entrada),
              trim(p_saida), trim(p_estorno), trim(p_consumo), trim(p_permanente), 
              trim(p_inativa_bem), trim(p_situacao_fisica), trim(p_ativo));
   Elsif p_operacao = 'A' Then
      --Altera registro
      update mt_situacao set
         nome                   = trim(p_nome),
         sigla                  = trim(p_sigla),
         entrada                = trim(p_entrada),
         saida                  = trim(p_saida),
         estorno                = trim(p_estorno),
         consumo                = trim(p_consumo),
         permanente             = trim(p_permanente),
         inativa_bem            = trim(p_inativa_bem),
         situacao_fisica        = trim(p_situacao_fisica),         
         ativo                  = trim(p_ativo)
       where sq_mtsituacao = p_chave;
   Elsif p_operacao = 'E' Then
      --Exclui registro
      DELETE FROM mt_situacao where sq_mtsituacao = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;