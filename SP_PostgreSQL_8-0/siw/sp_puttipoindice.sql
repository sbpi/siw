create or replace FUNCTION SP_PutTipoIndice
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_nome                      varchar,
    p_descricao                 varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_indice_tipo (sq_indice_tipo, nome, descricao)
      (select nextVal('sq_indice_tipo'), p_nome, p_descricao);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_indice_tipo set
         nome      = p_nome,
         descricao = p_descricao
       where sq_indice_tipo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_indice_tipo where sq_indice_tipo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;