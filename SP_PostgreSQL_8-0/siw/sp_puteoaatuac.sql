create or replace FUNCTION SP_PutEOAAtuac
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
     insert into eo_area_atuacao (sq_area_atuacao, sq_pessoa, nome, ativo)
         (select sq_area_atuacao.nextval,
                 p_cliente,
                 trim(p_nome),
                 p_ativo
            from dual
          );  
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_area_atuacao set
        nome  = trim(p_nome),
        ativo = p_ativo
        where sq_area_atuacao = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM eo_area_atuacao where sq_area_atuacao = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;