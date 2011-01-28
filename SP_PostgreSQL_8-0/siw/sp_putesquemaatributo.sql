create or replace FUNCTION SP_PutEsquemaAtributo
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_esquema_tabela         numeric,
    p_sq_coluna                 numeric,
    p_ordem                     numeric,
    p_campo_externo             varchar,
    p_mascara_data              varchar,
    p_valor_default             varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_esquema_atributo (sq_esquema_atributo, sq_esquema_tabela, sq_coluna, 
                  ordem, campo_externo, mascara_data, valor_default)
          (select sq_esquema_atributo.nextval,
                  p_sq_esquema_tabela,
                  p_sq_coluna,
                  p_ordem,
                  trim(p_campo_externo),
                  p_mascara_data,
                  p_valor_default
            
          );
   Elsif p_operacao = 'E' Then
      -- Apaga todos os registros, para q a exclusão e alteração seja feita
      DELETE FROM dc_esquema_atributo where sq_esquema_tabela = p_sq_esquema_tabela;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;