create or replace procedure sp_putGPFeriasDias
    (p_operacao in varchar2,
     p_chave          in number,
     p_cliente        in number,
     p_faixa_inicio   in number,
     p_faixa_fim      in number,
     p_dias_ferias    in number,
     p_ativo          in varchar2
     )is
     w_chave          number(18);
begin
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
    delete gp_ferias_dias where sq_ferias_dias = p_chave;   
  End if;   
end sp_putGPFeriasDias;
/
