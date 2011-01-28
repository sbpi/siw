create or replace FUNCTION SP_PutProjetoRec
   (p_operacao             varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_nome                varchar,
    p_tipo                numeric,
    p_descricao           varchar,
    p_finalidade          varchar  
   ) RETURNS VOID AS $$
DECLARE
   w_chave   numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera o valor da próxima chave
      select sq_projeto_recurso.nextval into  w_chave from dual;
      
      -- Insere registro na tabela de recursos
      Insert Into pj_projeto_recurso
         ( sq_projeto_recurso, sq_siw_solicitacao, nome,    tipo,   descricao,   finalidade )
      Values
         (  w_chave,           p_chave,            p_nome,  p_tipo, p_descricao, p_finalidade );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de recursos
      Update pj_projeto_recurso set
          nome         = p_nome,
          tipo         = p_tipo,
          descricao    = p_descricao,
          finalidade   = p_finalidade
      where sq_siw_solicitacao = p_chave
        and sq_projeto_recurso = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de recursos
      DELETE FROM pj_projeto_recurso 
       where sq_siw_solicitacao = p_chave
         and sq_projeto_recurso = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;