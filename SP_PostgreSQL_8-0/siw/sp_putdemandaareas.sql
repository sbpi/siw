create or replace FUNCTION SP_PutDemandaAreas
   (p_operacao             varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_papel               varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de áreas envolvidas
      Insert Into gd_demanda_envolv ( sq_unidade,  sq_siw_solicitacao, papel )
      (select p_chave_aux, p_chave, trim(p_papel) 
        
        where 0 = (select count(*) from gd_demanda_envolv where sq_unidade = p_chave_aux and sq_siw_solicitacao = p_chave)
      );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de áreas envolvidas
      Update gd_demanda_envolv set
          papel            = trim(p_papel)
      where sq_siw_solicitacao = p_chave
        and sq_unidade         = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de áreas envolvidas
      DELETE FROM gd_demanda_envolv  
       where sq_siw_solicitacao = p_chave
         and sq_unidade         = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;