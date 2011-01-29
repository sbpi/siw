create or replace FUNCTION sp_putTipoIndicador
   (p_operacao    varchar,
    p_cliente     varchar,
    p_chave       numeric,
    p_nome        varchar,
    p_ativo       varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao in ('I','C') Then
      -- Insere registro
      insert into eo_tipo_indicador
        (sq_tipo_indicador,         cliente,   nome,   ativo)
      values
        (nextVal('sq_tipo_indicador'), p_cliente, p_nome, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_tipo_indicador
         set nome            = p_nome,
             ativo           = p_ativo
       where sq_tipo_indicador = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM eo_tipo_indicador where sq_tipo_indicador = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;