create or replace FUNCTION SP_PutGrupoVeiculo
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   varchar,
    p_nome                      varchar,
    p_sigla                     varchar,
    p_descricao                 varchar,
    p_ativo                     varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_grupo_veiculo 
        (sq_grupo_veiculo, cliente, nome, sigla, descricao, ativo)
      values
        (nextVal('sq_grupo_veiculo'), p_cliente, p_nome, p_sigla, p_descricao, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update sr_grupo_veiculo
         set cliente       = p_cliente,
             nome          = p_nome,
             sigla         = p_sigla,
             descricao     = p_descricao,             
             ativo = p_ativo
       where sq_grupo_veiculo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM sr_grupo_veiculo where sq_grupo_veiculo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;