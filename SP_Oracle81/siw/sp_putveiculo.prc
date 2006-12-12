create or replace procedure SP_PutVeiculo
   (p_operacao                 in varchar2,
    p_chave                    in number   default null,
    p_chave_aux                in number,
    p_cliente                  in varchar2, 
    p_placa                    in varchar2 default null, 
    p_marca                    in varchar2 default null, 
    p_modelo                   in varchar2 default null, 
    p_combustivel              in varchar2 default null, 
    p_tipo                     in varchar2 default null, 
    p_potencia                 in varchar2 default null, 
    p_cilindrada               in varchar2 default null, 
    p_ano_modelo               in varchar2 default null, 
    p_ano_fabricacao           in varchar2 default null, 
    p_renavam                  in varchar2 default null, 
    p_chassi                   in varchar2 default null, 
    p_alugado                  in varchar2 default null, 
    p_ativo                    in varchar2 default null 
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_veiculo
          (sq_veiculo,        sq_tipo_veiculo,   cliente,   placa,    marca,  modelo,  combustivel,     tipo,  potencia,     cilindrada,  ano_modelo,   ano_fabricacao,  renavam,    chassi,    alugado,  ativo)
        values
          (sq_veiculo.nextval, p_chave_aux   , p_cliente, p_placa, p_marca, p_modelo, p_combustivel, p_tipo, p_potencia, p_cilindrada, p_ano_modelo,  p_ano_fabricacao, p_renavam, p_chassi, p_alugado, p_ativo);

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
      delete sr_veiculo where sq_veiculo = p_chave;
   End If;
end SP_PutVeiculo;
/
