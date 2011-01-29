create or replace FUNCTION sp_putSolicRecurso
   (p_operacao         varchar,
    p_usuario          numeric,
    p_chave            numeric,
    p_chave_aux        numeric,
    p_tipo             numeric,
    p_recurso          numeric,
    p_justificativa    varchar,
    p_inicio           date,
    p_fim              date,
    p_unidades         numeric   
   ) RETURNS VOID AS $$
DECLARE
   w_chave  numeric(18);
BEGIN
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select nextVal('sq_solic_recurso') into w_chave;

      -- Insere registro
      insert into siw_solic_recurso
        (sq_solic_recurso, sq_siw_solicitacao, sq_recurso, tipo,   solicitante, justificativa,   inclusao)
      values
        (w_chave,          p_chave,            p_recurso,  p_tipo, p_usuario,   p_justificativa, now());
      
      If p_inicio is not null Then
        -- Insere registro na tabela de alocações
        insert into siw_solic_recurso_alocacao
          (sq_solic_recurso_alocacao,         sq_solic_recurso, inicio,   fim,   unidades_solicitadas, unidades_autorizadas)
        values
          (nextVal('sq_solic_recurso_alocacao'), w_chave,          p_inicio, p_fim, p_unidades,           0);
      End If;
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_solic_recurso
         set sq_recurso    = p_recurso,
             tipo          = p_tipo,
             solicitante   = p_usuario,
             justificativa = p_justificativa,
             inclusao      = now()
       where sq_solic_recurso = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Elimina o recurso e seus períodos
      DELETE FROM siw_solic_recurso_alocacao where sq_solic_recurso = p_chave_aux;
      DELETE FROM siw_solic_recurso          where sq_solic_recurso = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;