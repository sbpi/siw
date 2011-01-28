create or replace FUNCTION sp_putGPFeriasDias
    (p_operacao varchar,
     p_chave          numeric,
     p_cliente        numeric,
     p_faixa_inicio   numeric,
     p_faixa_fim      numeric,
     p_dias_ferias    numeric,
     p_ativo          varchar
     )is
     w_chave          numeric(18);
BEGIN
  -- Grava os dias de direito de férias baseado no número de faltas sem justificativa
  If p_operacao = 'I' Then
  -- Recupera a próxima chave de sq_ferias_dias
     select sq_ferias_dias.nextval into w_chave from dual;
  -- Insere registro
    insert into gp_ferias_dias
      (sq_ferias_dias, cliente, faixa_inicio, faixa_fim, dias_ferias, ativo)
    values
      (w_chave, p_cliente, p_faixa_inicio, p_faixa_fim, p_dias_ferias, p_ativo);
  Elsif p_operacao = 'A' Then
  -- Altera registro        
    update gp_ferias_dias
       set faixa_inicio = p_faixa_inicio,
           faixa_fim = p_faixa_fim,
           dias_ferias = p_dias_ferias,
           ativo = p_ativo
     where sq_ferias_dias = p_chave;  
  Elsif p_operacao = 'E' Then
  -- Exclui o registro
    DELETE FROM gp_ferias_dias where sq_ferias_dias = p_chave;   
  End if;   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;