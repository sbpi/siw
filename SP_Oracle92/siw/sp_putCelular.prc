create or replace procedure SP_PutCelular
   (p_operacao                 in varchar2,
    p_cliente                  in number,
    p_chave                    in number   default null,
    p_numero                   in varchar2 default null,
    p_marca                    in varchar2 default null, 
    p_modelo                   in varchar2 default null, 
    p_sim_card                 in varchar2 default null, 
    p_imei                     in varchar2 default null, 
    p_ativo                    in varchar2 default null 
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into sr_celular
        (sq_celular,         cliente,   numero_linha, marca,   modelo,   sim_card,   imei,   ativo)
      values
        (sq_celular.nextval, p_cliente, p_numero,     p_marca, p_modelo, p_sim_card, p_imei, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update sr_celular
         set numero_linha = p_numero,
             marca        = p_marca,
             modelo       = p_modelo,
             sim_card     = p_sim_card,
             imei         = p_imei,
             ativo        = p_ativo
       where sq_celular = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete sr_celular where sq_celular = p_chave;
   End If;
end SP_PutCelular;
/
