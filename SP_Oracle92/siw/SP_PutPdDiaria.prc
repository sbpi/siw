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
    p_sq_diaria_hospedagem  in  number   default null,
    p_sq_diaria_veiculo     in  number   default null,
    p_justificativa_diaria  in  varchar2 default null,
    p_justificativa_veiculo in  varchar2 default null,
    p_rub_dia               in  number   default null,
    p_lan_dia               in  number   default null,
    p_fin_dia               in  number   default null,
    p_rub_hsp               in  number   default null,
    p_lan_hsp               in  number   default null,
    p_fin_hsp               in  number   default null,
    p_rub_vei               in  number   default null,
    p_lan_vei               in  number   default null,
    p_fin_vei               in  number   default null,
    p_hos_in                in  date     default null,
    p_hos_out               in  date     default null,
    p_hos_observ            in  varchar2 default null,
    p_vei_ret               in  date     default null,
    p_vei_dev               in  date     default null
   ) is
   w_reg      number(18);
   w_fin_dia  number(18) := p_fin_dia;
   w_fin_hsp  number(18) := p_fin_hsp;
   w_fin_vei  number(18) := p_fin_vei;
begin
   -- Verifica se precisa gravar o tipo de vínculo financeiro
   If instr('IA','I')>0 Then
      If p_fin_dia is null and p_lan_dia is not null Then
         -- Verifica se há um vínculo único para as opções enviadas
         select count(*) into w_reg
           from pd_vinculo_financeiro
          where sq_projeto_rubrica = p_rub_dia
            and sq_tipo_lancamento = p_lan_dia
            and diaria             = 'S';
         -- Prepara variável para gravação se encontrou um, e apenas um registro.
         If w_reg = 1 Then
            select sq_pdvinculo_financeiro into w_fin_dia
              from pd_vinculo_financeiro
             where sq_projeto_rubrica = p_rub_dia
               and sq_tipo_lancamento = p_lan_dia
               and diaria             = 'S';
         End If;
      End If;

      If p_fin_hsp is null and p_lan_hsp is not null Then
         -- Verifica se há um vínculo único para as opções enviadas
         select count(*) into w_reg
           from pd_vinculo_financeiro
          where sq_projeto_rubrica = p_rub_hsp
            and sq_tipo_lancamento = p_lan_hsp
            and diaria             = 'S';
         -- Prepara variável para gravação se encontrou um, e apenas um registro.
         If w_reg = 1 Then
            select sq_pdvinculo_financeiro into w_fin_hsp
              from pd_vinculo_financeiro
             where sq_projeto_rubrica = p_rub_hsp
               and sq_tipo_lancamento = p_lan_hsp
               and hospedagem         = 'S';
         End If;
      End If;

      If p_fin_vei is null and p_lan_vei is not null Then
         -- Verifica se há um vínculo único para as opções enviadas
         select count(*) into w_reg
           from pd_vinculo_financeiro
          where sq_projeto_rubrica = p_rub_vei
            and sq_tipo_lancamento = p_lan_vei
            and diaria             = 'S';
         -- Prepara variável para gravação se encontrou um, e apenas um registro.
         If w_reg = 1 Then
            select sq_pdvinculo_financeiro into w_fin_vei
              from pd_vinculo_financeiro
             where sq_projeto_rubrica = p_rub_vei
               and sq_tipo_lancamento = p_lan_vei
               and veiculo            = 'S';
         End If;
      End If;

   End If;

   If p_diaria = 'N' and p_hospedagem = 'N' and p_veiculo = 'N' Then
      delete pd_diaria where sq_siw_solicitacao = p_chave and sq_diaria = p_sq_diaria;
   Elsif p_operacao = 'I' Then
      -- Insere os registros em PD_DIARIA
      insert into pd_diaria
        (sq_diaria,                   sq_siw_solicitacao,              sq_cidade,              quantidade,                valor, 
         hospedagem,                  hospedagem_qtd,                  hospedagem_valor,       veiculo,                   veiculo_qtd, 
         veiculo_valor,               sq_valor_diaria,                 diaria,                 sq_deslocamento_chegada,   sq_deslocamento_saida, 
         sq_valor_diaria_hospedagem,  sq_valor_diaria_veiculo,         justificativa_diaria,   justificativa_veiculo,
         sq_pdvinculo_diaria,         sq_pdvinculo_hospedagem,         sq_pdvinculo_veiculo,   hospedagem_checkin,        hospedagem_checkout,
         hospedagem_observacao,       veiculo_retirada,                veiculo_devolucao)
      (select sq_diaria.nextval,      p_chave,                         p_sq_cidade,
              case p_diaria when 'S' then p_quantidade else 0 end,
              case p_diaria when 'S' then p_valor else 0 end,
              p_hospedagem,           
              case p_hospedagem when 'S' then coalesce(p_hospedagem_qtd,0) else 0 end,
              case p_hospedagem when 'S' then coalesce(p_hospedagem_valor,0) else 0 end,
              p_veiculo,              
              case p_veiculo when 'S' then coalesce(p_veiculo_qtd,0) else 0 end,
              case p_veiculo when 'S' then coalesce(p_veiculo_valor,0) else 0 end,
              case p_diaria when 'S' then p_sq_valor_diaria else null end, p_diaria,           p_deslocamento_chegada, p_deslocamento_saida, 
              p_sq_diaria_hospedagem, p_sq_diaria_veiculo,
              case p_diaria when 'N' then p_justificativa_diaria else null end,
              case p_veiculo when 'S' then p_justificativa_veiculo else null end,
              case p_diaria when 'S' then w_fin_dia else null end,
              case p_hospedagem when 'S' then w_fin_hsp else null end,
              case p_veiculo when 'S' then w_fin_vei else null end,
              case p_hospedagem when 'S' then p_hos_in else null end,
              case p_hospedagem when 'S' then p_hos_out else null end,
              case p_hospedagem when 'S' then p_hos_observ else null end,
              case p_veiculo when 'S' then p_vei_ret else null end,
              case p_veiculo when 'S' then p_vei_dev else null end
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
             sq_valor_diaria            = case p_diaria when 'S' then p_sq_valor_diaria else null end,
             diaria                     = p_diaria,
             sq_deslocamento_chegada    = p_deslocamento_chegada,
             sq_deslocamento_saida      = p_deslocamento_saida,
             sq_valor_diaria_hospedagem = p_sq_diaria_hospedagem,
             sq_valor_diaria_veiculo    = p_sq_diaria_veiculo,
             justificativa_diaria       = case p_diaria when 'N' then p_justificativa_diaria else null end,
             justificativa_veiculo      = case p_veiculo when 'S' then p_justificativa_veiculo else null end,
             sq_pdvinculo_diaria        = case p_diaria when 'S' then w_fin_dia else null end,
             sq_pdvinculo_hospedagem    = case p_hospedagem when 'S' then w_fin_hsp else null end,
             sq_pdvinculo_veiculo       = case p_veiculo when 'S' then w_fin_vei else null end,
             hospedagem_checkin         = case p_hospedagem when 'S' then p_hos_in else null end,
             hospedagem_checkout        = case p_hospedagem when 'S' then p_hos_out else null end,
             hospedagem_observacao      = case p_hospedagem when 'S' then p_hos_observ else null end,
             veiculo_retirada           = case p_veiculo when 'S' then p_vei_ret else null end,
             veiculo_devolucao          = case p_veiculo when 'S' then p_vei_dev else null end
       where sq_siw_solicitacao         = p_chave
         and sq_diaria                  = p_sq_diaria;
   End If;
end SP_PutPdDiaria;
/
