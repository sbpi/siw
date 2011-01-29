create or replace FUNCTION sp_putSolicRecAlocacao
   (p_operacao         varchar,
    p_usuario          numeric,
    p_chave            numeric,
    p_chave_aux        numeric,
    p_inicio           date,
    p_fim              date,
    p_unidades         numeric   
   ) RETURNS VOID AS $$
DECLARE
   w_chave  numeric(18);
BEGIN
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select nextVal('sq_solic_recurso_alocacao') into w_chave;

      -- Insere registro
      insert into siw_solic_recurso_alocacao
        (sq_solic_recurso_alocacao, sq_solic_recurso, inicio,   fim,   unidades_solicitadas, unidades_autorizadas)
      values
        (w_chave,                   p_chave,          p_inicio, p_fim, p_unidades,           0);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_solic_recurso_alocacao
         set inicio               = p_inicio,
             fim                  = p_fim,
             unidades_solicitadas = p_unidades
       where sq_solic_recurso_alocacao = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Recupera o período do registro
      DELETE FROM siw_solic_recurso_alocacao where sq_solic_recurso_alocacao = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;