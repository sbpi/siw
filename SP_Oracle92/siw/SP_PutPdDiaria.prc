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
    p_vei_dev               in  date     default null,
    p_tipo                  in varchar2  default null,
    p_origem                in varchar2  default null,
    p_texto_diaria          in varchar2  default null,
    p_texto_hospedagem      in varchar2  default null,
    p_texto_veiculo         in varchar2  default null
   ) is
   w_reg        number(18);
   w_sq_diaria  number(18) := p_sq_diaria;
   w_fin_dia    number(18) := p_fin_dia;
   w_fin_hsp    number(18) := p_fin_hsp;
   w_fin_vei    number(18) := p_fin_vei;
   w_diaria     number(4);
   w_hospedagem number(4);
   w_veiculo    number(4);
   w_sg_tramite varchar2(10);
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

   If p_operacao = 'I' Then
      -- Recupera o valor da chave
      select  sq_diaria.nextval into w_sq_diaria from dual;
      
      -- Insere os registros em PD_DIARIA
      insert into pd_diaria
        (sq_diaria,                   sq_siw_solicitacao,              sq_cidade,              quantidade,                valor, 
         hospedagem,                  hospedagem_qtd,                  hospedagem_valor,       veiculo,                   veiculo_qtd, 
         veiculo_valor,               sq_valor_diaria,                 diaria,                 sq_deslocamento_chegada,   sq_deslocamento_saida, 
         sq_valor_diaria_hospedagem,  sq_valor_diaria_veiculo,         justificativa_diaria,   justificativa_veiculo,
         sq_pdvinculo_diaria,         sq_pdvinculo_hospedagem,         sq_pdvinculo_veiculo,   hospedagem_checkin,        hospedagem_checkout,
         hospedagem_observacao,       veiculo_retirada,                veiculo_devolucao,      tipo,                      calculo_diaria_texto,
         calculo_hospedagem_texto,    calculo_veiculo_texto)
      (select w_sq_diaria,            p_chave,                         p_sq_cidade,
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
              p_justificativa_diaria,
              case p_veiculo when 'S' then p_justificativa_veiculo else null end,
              case p_diaria when 'S' then w_fin_dia else null end,
              case p_hospedagem when 'S' then w_fin_hsp else null end,
              case p_veiculo when 'S' then w_fin_vei else null end,
              case p_hospedagem when 'S' then p_hos_in else null end,
              case p_hospedagem when 'S' then p_hos_out else null end,
              p_hos_observ,
              case p_veiculo when 'S' then p_vei_ret else null end,
              case p_veiculo when 'S' then p_vei_dev else null end,
              p_tipo,
              p_texto_diaria,
              p_texto_hospedagem,
              p_texto_veiculo
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Atualiza os dados PD_DIARIA
      update pd_diaria a
         set sq_cidade                  = p_sq_cidade,
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
             justificativa_diaria       = p_justificativa_diaria,
             justificativa_veiculo      = case p_veiculo when 'S' then p_justificativa_veiculo else null end,
             sq_pdvinculo_diaria        = case p_diaria when 'S' then w_fin_dia else null end,
             sq_pdvinculo_hospedagem    = case p_hospedagem when 'S' then w_fin_hsp else null end,
             sq_pdvinculo_veiculo       = case p_veiculo when 'S' then w_fin_vei else null end,
             hospedagem_checkin         = case p_hospedagem when 'S' then p_hos_in else null end,
             hospedagem_checkout        = case p_hospedagem when 'S' then p_hos_out else null end,
             hospedagem_observacao      = p_hos_observ,
             veiculo_retirada           = case p_veiculo when 'S' then p_vei_ret else null end,
             veiculo_devolucao          = case p_veiculo when 'S' then p_vei_dev else null end,
             calculo_diaria_texto       = p_texto_diaria,
             calculo_hospedagem_texto   = p_texto_hospedagem,
             a.calculo_veiculo_texto    = p_texto_veiculo
       where sq_siw_solicitacao         = p_chave
         and sq_diaria                  = p_sq_diaria;
   End If;
   
   If p_origem = 'SOLIC' Then
      -- Se tela preenchida pelo solicitante, atualiza o valor calculado das hospedagens e das diárias de veículo
      update pd_diaria set calculo_hospedagem_qtd = hospedagem_qtd, calculo_veiculo_qtd = veiculo_qtd where sq_diaria =  w_sq_diaria;
      
      -- Verifica o trâmite da solicitação
      select b.sigla into w_sg_tramite
        from siw_solicitacao        a
             inner join siw_tramite b on (a.sq_siw_tramite = b.sq_siw_tramite)
       where a.sq_siw_solicitacao = p_chave;
       
      -- Verifica se alguma diária foi cadastrada para a solicitação
      select count(*) into w_reg from pd_diaria where sq_siw_solicitacao = p_chave;
       
      If w_sg_tramite <> 'CI' and w_reg > 0 Then
         -- Verifica se alguma das localidades necessita de diárias, hospedagens ou veículos
         select count(*) into w_diaria     from pd_diaria where sq_siw_solicitacao = p_chave and diaria     = 'S';
         select count(*) into w_hospedagem from pd_diaria where sq_siw_solicitacao = p_chave and hospedagem = 'S';
         select count(*) into w_veiculo    from pd_diaria where sq_siw_solicitacao = p_chave and veiculo    = 'S';
         
         update pd_missao
            set diaria     = case w_diaria     when 0 then null else diaria end,
                hospedagem = case w_hospedagem when 0 then 'N'  else 'S'    end,
                veiculo    = case w_veiculo    when 0 then 'N'  else 'S'    end
         where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- Ajusta as diárias se o usuário não as definiu manualmente
   If p_texto_diaria is null Then 
     sp_calculadiarias(p_chave, null, p_tipo); 
   End If;

   If p_operacao in ('I','A') Then
      -- Ajusta a quantidade de locações de veiculo quando a quantidade de diárias é menor
      update pd_diaria a
         set veiculo_qtd = quantidade
      where sq_diaria  = p_sq_diaria
        and quantidade < veiculo_qtd;
   End If;
end SP_PutPdDiaria;
/
