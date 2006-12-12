create or replace procedure SP_PutAbastecimento
   (p_operacao                 in varchar2,
    p_chave                    in number  default null,
    p_chave_aux                in number,
    p_data                     in date, 
    p_hodometro                in number,    
    p_litros                   in number,
    p_valor                    in number, 
    p_local                    in varchar2 
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_abastecimento
          (sq_abastecimento,         sq_veiculo,    data,   hodometro, litros,    valor,    local)
        values
          (sq_abastecimento.nextval, p_chave_aux, p_data, p_hodometro, p_litros, p_valor, p_local);

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
      delete sr_abastecimento where sq_abastecimento = p_chave;
   End If;
end SP_PutAbastecimento;
/
