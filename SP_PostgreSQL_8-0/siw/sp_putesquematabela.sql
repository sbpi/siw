create or replace FUNCTION SP_PutEsquemaTabela
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_esquema                numeric,
    p_sq_tabela                 numeric,
    p_ordem                     numeric,
    p_elemento                  varchar,
    p_remove_registro           varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_esquema_tabela (sq_esquema_tabela, sq_esquema, sq_tabela, ordem, 
                                     elemento, remove_registro)
         (select nextVal('sq_esquema_tabela'),
                 p_sq_esquema,
                 p_sq_tabela,
                 p_ordem,
                 trim(p_elemento),
                 p_remove_registro
           
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_esquema_tabela set
         ordem                = p_ordem,
         elemento             = trim(p_elemento),
         remove_registro      = p_remove_registro
      where sq_esquema_tabela    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_esquema_tabela where sq_esquema_tabela = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;