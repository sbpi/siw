create or replace FUNCTION sp_PutTipoRestricao
   (p_operacao  varchar,
    p_chave     numeric,
    p_cliente   numeric,
    p_nome      varchar,
    p_codigo    varchar,
    p_ativo     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_tipo_restricao (sq_tipo_restricao, cliente, nome, codigo_externo, ativo)
      (select sq_tipo_restricao.nextval, p_cliente, p_nome,  p_codigo, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_tipo_restricao
         set 
             cliente        = p_cliente,
             nome           = p_nome,
             codigo_externo = p_codigo,
             ativo          = p_ativo
       where sq_tipo_restricao = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM siw_tipo_restricao
       where sq_tipo_restricao = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;