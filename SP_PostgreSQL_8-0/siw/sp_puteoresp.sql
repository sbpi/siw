create or replace FUNCTION SP_PutEOResp
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_fim_substituto            date,
    p_sq_pessoa_substituto      numeric,
    p_inicio_substituto         date,
    p_fim_titular               date,
    p_sq_pessoa                 numeric,
    p_inicio_titular            date
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   DELETE FROM eo_unidade_resp where fim is null and sq_unidade = p_chave;
   If p_operacao <> 'E' Then
      If not p_sq_pessoa_substituto is null Then
         insert into eo_unidade_resp(
                     sq_unidade_resp, fim, sq_unidade, sq_pessoa, tipo_respons, inicio)         
             (select nextVal('sq_unidade_responsavel'),
                     p_fim_substituto,
                     p_chave,
                     p_sq_pessoa_substituto,
                     'S',
                     p_inicio_substituto
                     
            
         );
      End If;
      insert into eo_unidade_resp(
               sq_unidade_resp, fim, sq_unidade, sq_pessoa, tipo_respons, inicio)
       (select nextVal('sq_unidade_responsavel'),
               p_fim_titular,
               p_chave,
               p_sq_pessoa,
               'T',
               p_inicio_titular
              
        );
    End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;