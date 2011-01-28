create or replace FUNCTION SP_PutTrigEvento
   (p_operacao       varchar,
    p_chave          numeric,
    p_chave_aux      numeric 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro em dc_trigger_evento
      insert into dc_trigger_evento (sq_trigger, sq_evento)
      values (p_chave, p_chave_aux);
   Elsif p_operacao = 'E' Then
      -- Remove a associação entre a trigger e os eventos
      DELETE FROM dc_trigger_evento where sq_trigger = p_chave;
   End If;
   
   commit;   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;