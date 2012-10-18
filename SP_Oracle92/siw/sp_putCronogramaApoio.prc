create or replace procedure SP_PutCronogramaApoio
   (p_operacao            in  varchar2,
    p_chave               in number,
    p_chave_aux           in number    default null,
    p_valor_previsto      in number    default null,
    p_valor_real          in number    default null
   ) is
   w_cont number(10);
begin
   If p_operacao in ('I','A') Then
      -- Verifica se o registro existe
      select count(*) into w_cont
        from pj_cronograma_apoio
       where sq_rubrica_cronograma = p_chave
         and sq_solic_apoio        = p_chave_aux;
      
      If w_cont = 0 Then
         -- Insere registro na tabela de cronograma da fonte de financiamento
         insert into pj_cronograma_apoio
            (sq_rubrica_cronograma, sq_solic_apoio, valor_previsto,   valor_real)
         values
            (p_chave,               p_chave_aux,    p_valor_previsto, coalesce(p_valor_real,0));
      Else 
         -- Altera o valor previsto do cronograma da fonte de financiamento
         update pj_cronograma_apoio
            set valor_previsto = p_valor_previsto
          where sq_rubrica_cronograma = p_chave
            and sq_solic_apoio        = p_chave_aux;
      End If;
   Elsif p_operacao = 'V' Then 
      -- Registra o valor realizado do cronograma da fonte de financiamento
      update pj_cronograma_apoio
         set valor_real     = p_valor_real
       where sq_rubrica_cronograma = p_chave
         and sq_solic_apoio        = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove registros vinculados
      delete pj_cronograma_apoio where sq_rubrica_cronograma = p_chave_aux;
      
      -- Remove o registro na tabela de cronograma da rubrica
      delete pj_rubrica_cronograma where sq_rubrica_cronograma = p_chave_aux;
   End If;
   
   -- Atualiza o valor total da fonte de financiamento
   update siw_solic_apoio
      set valor = (select sum(valor_previsto) from pj_cronograma_apoio where sq_solic_apoio = p_chave_aux)
   where sq_solic_apoio = p_chave_aux;
   
end SP_PutCronogramaApoio;
/
