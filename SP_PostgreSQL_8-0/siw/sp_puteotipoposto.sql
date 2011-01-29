create or replace FUNCTION SP_PutEOTipoPosto
   (p_operacao             varchar,
    p_chave                numeric,
    p_cliente              numeric,
    p_nome                 varchar,
    p_sigla                varchar,
    p_descricao            varchar,
    p_ativo                varchar,
    p_padrao               varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de tipos de posto
      insert into eo_tipo_posto
        (sq_eo_tipo_posto,      cliente,    nome,       sigla,
         descricao,             ativo,      padrao)
      (select 
         nextVal('sq_tipo_posto'), p_cliente,  p_nome,     p_sigla,
         p_descricao,           p_ativo,    p_padrao
      );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de tipos de posto
      update eo_tipo_posto
         set nome      = p_nome,
             sigla     = p_sigla,
             descricao = p_descricao,
             ativo     = p_ativo,
             padrao    = p_padrao
       where sq_eo_tipo_posto = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de tipos de posto
      DELETE FROM eo_tipo_posto
       where sq_eo_tipo_posto = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;