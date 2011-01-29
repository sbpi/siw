create or replace FUNCTION SP_PutTabela
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_tabela_tipo            varchar,
    p_sq_usuario                varchar,
    p_sq_sistema                numeric,
    p_nome                      varchar,
    p_descricao                 varchar  
    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
   insert into dc_tabela
     (sq_tabela, sq_tabela_tipo, sq_usuario, sq_sistema, nome, descricao)
   (select nextVal('sq_tabela'),  p_sq_tabela_tipo, p_sq_usuario, p_sq_sistema, p_nome, p_descricao);
   Elsif p_operacao = 'A' Then
      -- Altera registro
   update dc_tabela
      set 
          sq_tabela_tipo = p_sq_tabela_tipo,
          sq_usuario     = p_sq_usuario,
          sq_sistema     = p_sq_sistema,
          nome           = p_nome,
          descricao      = p_descricao
    where sq_tabela      = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_tabela
       where sq_tabela = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;