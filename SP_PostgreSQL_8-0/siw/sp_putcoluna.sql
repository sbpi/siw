create or replace FUNCTION SP_PutColuna
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_tabela                 numeric,
    p_sq_dado_tipo              numeric,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_ordem                     numeric,
    p_tamanho                   numeric,
    p_precisao                  numeric,
    p_escala                    numeric,    
    p_obrigatorio               varchar,    
    p_valor_padrao              varchar   
    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
   insert into dc_coluna
     (sq_coluna, sq_tabela, sq_dado_tipo, nome, descricao, ordem, tamanho, precisao, escala, obrigatorio, valor_padrao)
   (select sq_coluna.nextval, p_sq_tabela, p_sq_dado_tipo, p_nome, p_descricao, p_ordem, p_tamanho, p_precisao, p_escala, p_obrigatorio, p_valor_padrao);
   Elsif p_operacao = 'A' Then
      -- Altera registro
   update dc_coluna
      set 
          sq_tabela = p_sq_tabela,
          sq_dado_tipo = p_sq_dado_tipo,
          nome = p_nome,
          descricao = p_descricao,
          ordem = p_ordem,
          tamanho = p_tamanho,
          precisao = p_precisao,
          escala = p_escala,
          obrigatorio = p_obrigatorio,
          valor_padrao = p_valor_padrao
    where sq_coluna = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_coluna
       where sq_coluna = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;