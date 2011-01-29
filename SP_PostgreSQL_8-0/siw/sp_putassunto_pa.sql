create or replace FUNCTION sp_PutAssunto_PA
   (p_operacao          varchar,
    p_chave             numeric,
    p_cliente           numeric,
    p_chave_pai         numeric,
    p_codigo            varchar,
    p_descricao         varchar,
    p_detalhamento      varchar,
    p_observacao        varchar,
    p_corrente_guarda   numeric,
    p_corrente_anos     numeric,
    p_intermed_guarda   numeric,
    p_intermed_anos     numeric,
    p_final_guarda      numeric,
    p_final_anos        numeric,
    p_destinacao_final  numeric,
    p_provisorio        varchar,
    p_ativo             varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_assunto 
         (sq_assunto, cliente, sq_assunto_pai, codigo, descricao, detalhamento, observacao, fase_corrente_guarda,
          fase_corrente_anos, fase_intermed_guarda, fase_intermed_anos, fase_final_guarda,
          fase_final_anos, destinacao_final, provisorio, ativo)
      (select nextVal('sq_assunto'), p_cliente, p_chave_pai, p_codigo, p_descricao, p_detalhamento, p_observacao, p_corrente_guarda, 
              p_corrente_anos, p_intermed_guarda, p_intermed_anos, p_final_guarda, p_final_anos, 
              p_destinacao_final, p_provisorio, p_ativo 
        
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_assunto
         set sq_assunto_pai       = p_chave_pai,
             codigo               = p_codigo,
             descricao            = p_descricao,
             detalhamento         = p_detalhamento,
             observacao           = p_observacao,
             fase_corrente_guarda = p_corrente_guarda,
             fase_corrente_anos   = p_corrente_anos,
             fase_intermed_guarda = p_intermed_guarda,
             fase_intermed_anos   = p_intermed_anos,
             fase_final_guarda    = p_final_guarda,
             fase_final_anos      = p_final_anos,
             destinacao_final     = p_destinacao_final,
             provisorio           = p_provisorio,
             ativo                = p_ativo
       where sq_assunto = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pa_assunto
       where sq_assunto = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;