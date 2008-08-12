create or replace procedure SP_PutPdDiaria
   (p_operacao              in  varchar2,
    p_chave                 in  number   default null,
    p_sq_diaria             in  number   default null,
    p_sq_cidade             in  number   default null,
    p_diaria                in  varchar2 default null,
    p_quantidade            in  number   default null,
    p_valor                 in  number   default null,
    p_hospedagem            in  varchar2 default null,
    p_hospedagem_qtd        in  number   default null,
    p_hospedagem_valor      in  number   default null,
    p_veiculo               in  varchar2 default null,
    p_veiculo_qtd           in  number   default null,
    p_veiculo_valor         in  number   default null,
    p_deslocamento_chegada  in  number   default null,
    p_deslocamento_saida    in  number   default null,
    p_sq_valor_diaria       in  number   default null,
    p_sq_diaria_hospedagem   in  number   default null,
    p_sq_diaria_veiculo      in  number   default null,
    p_justificativa_diaria  in  varchar2  default null,
    p_justificativa_veiculo in  varchar2  default null
   ) is
begin
   If p_diaria = 'N' and p_hospedagem = 'N' and p_veiculo = 'N' Then
      delete pd_diaria where sq_siw_solicitacao = p_chave and sq_diaria = p_sq_diaria;
   Elsif p_operacao = 'I' Then
      -- Insere os registros em PD_DIARIA
      insert into pd_diaria
        (sq_diaria,                   sq_siw_solicitacao,              sq_cidade,              quantidade,                valor, 
         hospedagem,                  hospedagem_qtd,                  hospedagem_valor,       veiculo,                   veiculo_qtd, 
         veiculo_valor,               sq_valor_diaria,                 diaria,                 sq_deslocamento_chegada,   sq_deslocamento_saida, 
         sq_valor_diaria_hospedagem,  sq_valor_diaria_veiculo,         justificativa_diaria,   justificativa_veiculo)
      (select sq_diaria.nextval,      p_chave,                         p_sq_cidade,
              case p_diaria when 'S' then p_quantidade else 0 end,
              case p_diaria when 'S' then p_valor else 0 end,
              p_hospedagem,           
              case p_hospedagem when 'S' then coalesce(p_hospedagem_qtd,0) else 0 end,
              case p_hospedagem when 'S' then coalesce(p_hospedagem_valor,0) else 0 end,
              p_veiculo,              
              case p_veiculo when 'S' then coalesce(p_veiculo_qtd,0) else 0 end,
              case p_veiculo when 'S' then coalesce(p_veiculo_valor,0) else 0 end,
              p_sq_valor_diaria,      p_diaria,                        p_deslocamento_chegada, p_deslocamento_saida, 
              p_sq_diaria_hospedagem, p_sq_diaria_veiculo,
              case p_diaria when 'S' then p_justificativa_diaria else null end,
              case p_veiculo when 'S' then p_justificativa_veiculo else null end
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Atualiza os dados PD_DIARIA
      update pd_diaria
         set sq_diaria                  = p_sq_diaria,
             sq_siw_solicitacao         = p_chave,
             sq_cidade                  = p_sq_cidade,
             quantidade                 = case p_diaria when 'S' then p_quantidade else 0 end,
             valor                      = case p_diaria when 'S' then p_valor else 0 end,
             hospedagem                 = p_hospedagem,
             hospedagem_qtd             = case p_hospedagem when 'S' then coalesce(p_hospedagem_qtd,0)  else 0 end,
             hospedagem_valor           = case p_hospedagem when 'S' then coalesce(p_hospedagem_valor,0)  else 0 end,
             veiculo                    = p_veiculo,
             veiculo_qtd                = case p_veiculo when 'S' then coalesce(p_veiculo_qtd,0) else 0 end,
             veiculo_valor              = case p_veiculo when 'S' then coalesce(p_veiculo_valor,0) else 0 end,
             sq_valor_diaria            = p_sq_valor_diaria,
             diaria                     = p_diaria,
             sq_deslocamento_chegada    = p_deslocamento_chegada,
             sq_deslocamento_saida      = p_deslocamento_saida,
             sq_valor_diaria_hospedagem = p_sq_diaria_hospedagem,
             sq_valor_diaria_veiculo    = p_sq_diaria_veiculo,
             justificativa_diaria       = case p_diaria when 'S' then p_justificativa_diaria else null end,
             justificativa_veiculo      = case p_veiculo when 'S' then p_justificativa_veiculo else null end
       where sq_siw_solicitacao         = p_chave
         and sq_diaria                  = p_sq_diaria;
   End If;
end SP_PutPdDiaria;
/
