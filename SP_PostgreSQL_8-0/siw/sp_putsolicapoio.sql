create or replace FUNCTION SP_PutSolicApoio
   (p_restricao                 varchar,
    p_chave                     varchar,
    p_chave_aux                 numeric,
    p_sq_tipo_apoio             numeric,
    p_entidade                  varchar,
    p_descricao                 varchar,
    p_valor                     numeric,
    p_usuario                   numeric
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_restricao = 'I' Then
      -- Insere registro
      insert into siw_solic_apoio
        (sq_solic_apoio, sq_siw_solicitacao, sq_tipo_apoio, entidade, descricao, valor, 
         sq_pessoa_atualizacao, ultima_atualizacao)
      values
        (sq_solic_apoionextVal(''), p_chave, p_sq_tipo_apoio, p_entidade, p_descricao, p_valor,
         p_usuario, now());
   Elsif p_restricao = 'A' Then
      -- Altera registro
      update siw_solic_apoio
         set sq_tipo_apoio         = p_sq_tipo_apoio,
             entidade              = p_entidade,
             descricao             = p_descricao,
             valor                 = p_valor,
             sq_pessoa_atualizacao = p_usuario,
             ultima_atualizacao    = now()
       where sq_siw_solicitacao = p_chave
         and sq_solic_apoio     = p_chave_aux;
   Elsif p_restricao = 'E' Then
      DELETE FROM siw_solic_apoio where sq_solic_apoio = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;