create or replace FUNCTION SP_PutSPSP
   (p_operacao    varchar,
    p_chave       numeric,
    p_chave_aux   numeric
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro em dc_trigger_evento
      insert into dc_sp_sp (sp_pai, sp_filha) values (p_chave, p_chave_aux);
   Elsif p_operacao = 'E' Then
      -- Remove a associação entre a trigger e os eventos
      DELETE FROM dc_sp_sp where sp_pai = p_chave and sp_filha = p_chave_aux;
   End If;
   
   commit;   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;