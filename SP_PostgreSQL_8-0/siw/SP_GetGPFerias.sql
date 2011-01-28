create or replace FUNCTION SP_getGPFerias
       (p_chave     numeric,
        p_chave_aux numeric,
        p_restricao varchar,
        p_result    REFCURSOR
       ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
  If p_restricao = 'LISTA' Then
     -- Recupera os recursos do projeto
     open p_result for 
        select a.*
          from pj_projeto_recurso  a
         where a.sq_siw_solicitacao = p_chave;
  Elsif p_restricao = 'REGISTRO' Then
     -- Recupera os dados de um recurso do projeto
     open p_result for 
        select a.*
          from pj_projeto_recurso a
         where a.sq_projeto_recurso = p_chave_aux;
  End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;