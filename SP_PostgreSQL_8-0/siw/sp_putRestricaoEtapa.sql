create or replace FUNCTION SP_PutRestricaoEtapa
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_sq_projeto_etapa         numeric
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_restricao_etapa (sq_siw_restricao, sq_projeto_etapa) 
      (select p_chave, a.sq_projeto_etapa
         from pj_projeto_etapa a
        where 0 = (select count(*) from siw_restricao_etapa where sq_siw_restricao = p_chave and sq_projeto_etapa = a.sq_projeto_etapa)
          and a.sq_projeto_etapa in (select sq_projeto_etapa from connectby('pj_projeto_etapa','sq_projeto_etapa','sq_etapa_pai',to_char(p_sq_projeto_etapa),0) as (sq_projeto_etapa numeric, sq_etapa_pai numeric, level int))
      );
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM siw_restricao_etapa 
       where (p_chave              is null or (p_chave                is not null and sq_siw_restricao  = p_chave))
         and (p_sq_projeto_etapa   is null or (p_sq_projeto_etapa     is not null and sq_projeto_etapa  = p_sq_projeto_etapa));
   End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;