create or replace FUNCTION sp_putRecurso_Disp
   (p_operacao         varchar,
    p_usuario          numeric,
    p_chave            numeric,
    p_chave_aux        numeric,
    p_limite_diario    numeric,
    p_valor            numeric,
    p_dia_util         varchar,
    p_inicio           date,
    p_fim              date,
    p_unidades         numeric   
   ) RETURNS VOID AS $$
DECLARE
   w_chave  numeric(18);
BEGIN
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select sq_recurso_disponivel.nextval into w_chave from dual;

      -- Insere registro
      insert into eo_recurso_disponivel
        (sq_recurso_disponivel, sq_recurso, inicio,   fim,   valor,   unidades,   limite_diario,   dia_util)
      values
        (w_chave,                 p_chave,      p_inicio, p_fim, p_valor, p_unidades, p_limite_diario, p_dia_util);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_recurso_disponivel
         set inicio        = p_inicio,
             fim           = p_fim,
             valor         = p_valor,
             unidades      = p_unidades,
             limite_diario = p_limite_diario,
             dia_util      = p_dia_util
       where sq_recurso_disponivel = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Recupera o período do registro
      DELETE FROM eo_recurso_disponivel   where sq_recurso_disponivel = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;