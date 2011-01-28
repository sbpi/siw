create or replace FUNCTION sp_putRecurso_Indisp
   (p_operacao         varchar,
    p_usuario          numeric,
    p_chave            numeric,
    p_chave_aux        numeric,
    p_inicio           date,
    p_fim              date,
    p_justificativa    varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_chave numeric(18);
BEGIN
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select sq_recurso_indisponivel.nextval into w_chave;

      -- Insere registro
      insert into eo_recurso_indisponivel
        (sq_recurso_indisponivel, sq_recurso, inicio,   fim,   justificativa)
      values
        (w_chave,                   p_chave,      p_inicio, p_fim, p_justificativa);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_recurso_indisponivel
         set inicio        = p_inicio,
             fim           = p_fim,
             justificativa = p_justificativa
       where sq_recurso_indisponivel = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui o recurso e seu cronograma de indisponibilidade
      DELETE FROM eo_recurso_indisponivel where sq_recurso_indisponivel = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;