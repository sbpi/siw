create or replace FUNCTION sp_PutTipoGuarda_PA
   (p_operacao          varchar,
    p_chave             numeric,
    p_cliente           numeric,
    p_sigla             varchar,
    p_descricao         varchar,
    p_fase_corrente     varchar,
    p_fase_intermed     varchar,
    p_fase_final        varchar,
    p_destinacao_final  varchar,
    p_ativo             varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_tipo_guarda (sq_tipo_guarda, cliente, sigla, descricao, fase_corrente, 
                                  fase_intermed, fase_final, destinacao_final, ativo)
      (select sq_tipo_guarda.nextval, p_cliente, upper(p_sigla), p_descricao, p_fase_corrente, 
              p_fase_intermed, p_fase_final, p_destinacao_final, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_tipo_guarda
         set cliente          = p_cliente,
             sigla            = upper(p_sigla),
             descricao        = p_descricao,
             fase_corrente    = p_fase_corrente,
             fase_intermed    = p_fase_intermed,
             fase_final       = p_fase_final,
             destinacao_final = p_destinacao_final,
             ativo            = p_ativo
       where sq_tipo_guarda = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pa_tipo_guarda
       where sq_tipo_guarda = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;