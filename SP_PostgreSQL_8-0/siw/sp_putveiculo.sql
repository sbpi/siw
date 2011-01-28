create or replace FUNCTION SP_PutVeiculo
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_chave_aux                numeric,
    p_cliente                  varchar, 
    p_placa                    varchar, 
    p_marca                    varchar, 
    p_modelo                   varchar, 
    p_combustivel              varchar, 
    p_tipo                     varchar, 
    p_potencia                 varchar, 
    p_cilindrada               varchar, 
    p_ano_modelo               varchar, 
    p_ano_fabricacao           varchar, 
    p_renavam                  varchar, 
    p_chassi                   varchar, 
    p_alugado                  varchar, 
    p_ativo                    varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_veiculo
          (sq_veiculo,        sq_tipo_veiculo,   cliente,   placa,    marca,  modelo,  combustivel,     tipo,  potencia,     cilindrada,  ano_modelo,   ano_fabricacao,  renavam,    chassi,    alugado,  ativo)
        values
          (sq_veiculonextVal(''), p_chave_aux, p_cliente, p_placa, p_marca, p_modelo, p_combustivel, p_tipo, p_potencia, p_cilindrada, p_ano_modelo,  p_ano_fabricacao, p_renavam, p_chassi, p_alugado, p_ativo);

   Elsif p_operacao = 'A' Then
      -- Altera registro
      update sr_veiculo
         set cliente          = p_cliente,
             sq_tipo_veiculo  = p_chave_aux,
             placa            = p_placa,
             marca            = p_marca,
             modelo           = p_modelo,
             combustivel      = p_combustivel,
             tipo             = p_tipo,
             potencia         = p_potencia,
             cilindrada       = p_cilindrada,
             ano_modelo       = p_ano_modelo,
             ano_fabricacao   = p_ano_fabricacao,
             renavam          = p_renavam,
             chassi           = p_chassi,
             alugado          = p_alugado,
             ativo            = p_ativo
       where sq_veiculo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM sr_veiculo where sq_veiculo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;