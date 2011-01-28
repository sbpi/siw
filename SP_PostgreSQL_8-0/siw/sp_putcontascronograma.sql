create or replace FUNCTION SP_PutContasCronograma
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_siw_solicitacao          numeric,
    p_prestacao_contas         numeric, 
    p_inicio                   date,
    p_fim                      date,
    p_limite                   date
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_contas_cronograma
          (sq_contas_cronograma,         sq_siw_solicitacao, sq_prestacao_contas, inicio,   fim,   limite)
        values
          (sq_contas_cronograma.nextval, p_siw_solicitacao,  p_prestacao_contas,  p_inicio, p_fim, p_limite);

   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_contas_cronograma
         set inicio           = p_inicio,
             fim              = p_fim,
             limite           = p_limite
       where sq_contas_cronograma = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM siw_contas_registro   where sq_contas_cronograma = p_chave;
      DELETE FROM siw_contas_cronograma where sq_contas_cronograma = p_chave;
   Elsif p_operacao = 'F' Then
      -- Exclui registro
      DELETE FROM siw_contas_registro   where sq_contas_cronograma in (select x.sq_contas_cronograma
                                                                    from siw_contas_cronograma x
                                                                   where x.sq_siw_solicitacao = p_siw_solicitacao);
      DELETE FROM siw_contas_cronograma where sq_siw_solicitacao = p_siw_solicitacao;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;