create or replace FUNCTION SP_PutEOTipoUni
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_nome                      varchar,
    p_ativo                     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
     insert into eo_tipo_unidade (sq_tipo_unidade, sq_pessoa, nome, ativo)
         (select nextVal('sq_tipo_unidade'),
                 p_cliente,
                 trim(p_nome),
                 p_ativo
           
          );  
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_tipo_unidade set
        nome  = trim(p_nome),
        ativo = p_ativo
        where sq_tipo_unidade = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM eo_tipo_unidade where sq_tipo_unidade = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;