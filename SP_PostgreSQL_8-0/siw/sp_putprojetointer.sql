create or replace FUNCTION SP_PutProjetoInter
   (p_operacao             varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_tipo_visao          varchar,
    p_envia_email         varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de interessados
      Insert Into pj_projeto_interes 
         ( sq_pessoa,   sq_siw_solicitacao, tipo_visao,    envia_email )
      Values
         (p_chave_aux,  p_chave,            p_tipo_visao,  p_envia_email );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update pj_projeto_interes set
          tipo_visao       = p_tipo_visao,
          envia_email      = p_envia_email
      where sq_siw_solicitacao = p_chave
        and sq_pessoa          = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de projetos
      DELETE FROM pj_projeto_interes 
       where sq_siw_solicitacao = p_chave
         and sq_pessoa          = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;