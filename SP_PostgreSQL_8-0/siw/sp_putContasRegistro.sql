create or replace FUNCTION SP_PutContasRegistro
   (p_operacao             varchar,
    p_chave               numeric,
    p_contas_cronograma   numeric,
    p_prestacao_contas    numeric,
    p_pendencia           varchar,
    p_observacao          varchar,
    p_usuario             numeric    
   ) RETURNS VOID AS $$
DECLARE
   w_chave    numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_contas_registro.nextval into w_chave from dual;
      
      -- Insere registro na tabela de registros de cronogramas
      Insert Into siw_contas_registro
         ( sq_contas_registro,   sq_contas_cronograma, sq_prestacao_contas, pendencia,   observacao)
      Values
         ( w_chave,              p_contas_cronograma,  p_prestacao_contas,  p_pendencia, p_observacao);
      update siw_contas_cronograma set
         sq_pessoa_atualizacao = p_usuario,
         ultima_atualizacao    = now()
       where sq_contas_cronograma = p_contas_cronograma; 
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de cronograma da rubrica
      DELETE FROM siw_contas_registro where sq_contas_cronograma = p_contas_cronograma;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;