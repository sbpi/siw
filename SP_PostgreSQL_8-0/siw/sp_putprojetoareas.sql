create or replace FUNCTION SP_PutProjetoAreas
   (p_operacao             varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_interesse           varchar,
    p_influencia          numeric,
    p_papel               varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de áreas envolvidas
      Insert Into pj_projeto_envolv 
         ( sq_unidade,  sq_siw_solicitacao,  interesse_positivo,    influencia,   papel )
      Values
         ( p_chave_aux, p_chave,                     p_interesse,  p_influencia,  trim(p_papel) );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de áreas envolvidas
      Update pj_projeto_envolv set
          interesse_positivo   = p_interesse,
          influencia           = p_influencia,
          papel                = trim(p_papel)
      where sq_siw_solicitacao = p_chave
        and sq_unidade         = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de áreas envolvidas
      DELETE FROM pj_projeto_envolv  
       where sq_siw_solicitacao = p_chave
         and sq_unidade         = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;