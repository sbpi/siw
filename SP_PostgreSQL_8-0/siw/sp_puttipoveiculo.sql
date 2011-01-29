create or replace FUNCTION SP_PutTipoVeiculo
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   varchar,
    p_chave_aux                 numeric,
    p_nome                      varchar,
    p_sigla                     varchar,
    p_descricao                 varchar,
    p_ativo                     varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_tipo_veiculo 
        (sq_tipo_veiculo, cliente, sq_grupo_veiculo, nome, sigla, descricao, ativo)
      values
        (nextVal('sq_tipo_veiculo'), p_cliente, p_chave_aux, p_nome, p_sigla, p_descricao, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update sr_tipo_veiculo
         set cliente          = p_cliente,
             nome             = p_nome,
             sq_grupo_veiculo = p_chave_aux,             
             sigla            = p_sigla,
             descricao        = p_descricao,             
             ativo = p_ativo
       where sq_tipo_veiculo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM sr_tipo_veiculo where sq_tipo_veiculo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;