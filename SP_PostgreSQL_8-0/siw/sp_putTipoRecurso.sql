create or replace FUNCTION sp_putTipoRecurso
   (p_operacao    varchar,
    p_cliente     varchar,
    p_chave       numeric,
    p_chave_pai   numeric,
    p_nome        varchar,
    p_sigla       varchar,
    p_gestora     numeric,
    p_descricao   varchar,
    p_ativo       varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao in ('I','C') Then
      -- Insere registro
      insert into eo_tipo_recurso
        (sq_tipo_recurso,         cliente,   sq_tipo_pai, nome,   sigla,          unidade_gestora,  descricao,   ativo)
      values
        (nextVal('sq_tipo_recurso'), p_cliente, p_chave_pai, p_nome, upper(p_sigla), p_gestora,        p_descricao, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_tipo_recurso
         set sq_tipo_pai     = p_chave_pai,
             nome            = p_nome,
             sigla           = upper(p_sigla),
             unidade_gestora = p_gestora,
             descricao       = p_descricao
       where sq_tipo_recurso = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM eo_tipo_recurso where sq_tipo_recurso = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa registro
      update eo_tipo_recurso set ativo = 'S' where sq_tipo_recurso = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa registro
      update eo_tipo_recurso set ativo = 'N' where sq_tipo_recurso = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;