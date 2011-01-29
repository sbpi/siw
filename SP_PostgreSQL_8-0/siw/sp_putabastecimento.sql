create or replace FUNCTION SP_PutAbastecimento
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_chave_aux                numeric,
    p_data                     date, 
    p_hodometro                numeric,    
    p_litros                   numeric,
    p_valor                    numeric, 
    p_local                    varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_abastecimento
          (sq_abastecimento,         sq_veiculo,    data,   hodometro, litros,    valor,    local)
        values
          (nextVal('sq_abastecimento'), p_chave_aux, p_data, p_hodometro, p_litros, p_valor, p_local);

   Elsif p_operacao = 'A' Then
      -- Altera registro
      update sr_abastecimento
         set sq_veiculo       = p_chave_aux,
             data             = p_data,
             hodometro        = p_hodometro,
             litros           = p_litros,
             valor            = p_valor,
             local            = p_local
       where sq_abastecimento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM sr_abastecimento where sq_abastecimento = p_chave;
   End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;
