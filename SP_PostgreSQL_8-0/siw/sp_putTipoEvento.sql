create or replace FUNCTION sp_putTipoEvento
   (p_operacao    varchar,
    p_menu     varchar,
    p_chave       numeric,
    p_nome        varchar,
    p_ordem       varchar,
    p_sigla       varchar,
    p_descricao   varchar,
    p_ativo       varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_tipo_evento
        (sq_tipo_evento,        sq_menu,   nome,   ordem,          sigla,   descricao,   ativo)
      values
        (nextVal('sq_tipo_evento'), p_menu, p_nome, p_ordem, upper(p_sigla), p_descricao, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_tipo_evento
         set nome          = p_nome,
             ordem         = p_ordem,
             sigla         = upper(p_sigla),
             descricao     = p_descricao,
             ativo         = p_ativo
       where sq_tipo_evento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM siw_tipo_evento where sq_tipo_evento = p_chave;
   End If;