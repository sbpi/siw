create or replace FUNCTION SP_PutSiwTramiteFluxo
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_destino                   numeric    
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' and p_chave is not null and p_destino is not null Then
      -- Insere registro
      insert into siw_tramite_fluxo
             (sq_siw_tramite_origem, sq_siw_tramite_destino)
      (select p_chave,               p_destino
        
      );
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM siw_tramite_fluxo where sq_siw_tramite_origem = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;