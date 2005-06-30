create or replace procedure sp_PutXMLLocalizador_PPA
   (p_cliente          in number   default null,
    p_ano              in number   default null,
    p_cd_programa      in varchar2 default null,
    p_cd_acao_ppa      in varchar2 default null,
    p_chave            in varchar2 default null,
    p_cd_localizador   in varchar2 default null,
    p_cd_regiao        in varchar2 default null,
    p_cd_municipio     in varchar2 default null,
    p_nome             in varchar2 default null,
    p_valor_total      in number   default null,
    p_valor_ano_ant    in number   default null,
    p_qtd_ano_ant      in number   default null,
    p_valor_ano_cor    in number   default null,
    p_qtd_ano_cor      in number   default null,
    p_reperc_financ    in varchar2 default null,
    p_vr_reperc_financ in number   default null,
    p_mes_ini          in varchar2 default null,
    p_ano_ini          in varchar2 default null,
    p_mes_fim          in varchar2 default null,
    p_ano_fim          in varchar2 default null,
    p_nome_alterado    in varchar2 default null,
    p_observacao       in varchar2 default null,
    p_qtd_total        in number   default null,
    p_cd_sof_ref       in number   default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);

begin
   select count(*) into w_cont 
     from is_ppa_localizador a 
    where a.cd_localizador_ppa = p_chave
      and a.cd_acao_ppa        = p_cd_acao_ppa
      and a.cd_programa        = p_cd_programa
      and a.cliente            = p_cliente
      and a.ano                = p_ano;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
      
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_ppa_localizador (cliente, ano, cd_programa, cd_acao_ppa, cd_localizador_ppa, cd_localizador,
                                      cd_regiao, cd_municipio, nome, valor_total, valor_ano_anterior, qtd_ano_anterior,
                                      valor_ano_corrente, qtd_ano_corrente, reperc_financeira, valor_reperc_financeira,
                                      mes_inicio, ano_inicio, mes_termino, ano_termino, nome_alterado, observacao, qtd_total, cd_sof_referencia,
                                      flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_cd_programa, p_cd_acao_ppa, p_chave, p_cd_localizador,
              p_cd_regiao, p_cd_municipio, p_nome, p_valor_total, p_valor_ano_ant, p_qtd_ano_ant, 
              p_valor_ano_cor, p_qtd_ano_cor, p_reperc_financ, p_vr_reperc_financ, 
              p_mes_ini, p_ano_ini, p_mes_fim, p_ano_fim, p_nome_alterado, 
              p_observacao, p_qtd_total, p_cd_sof_ref,
              sysdate, sysdate);
   Else
      -- Altera registro
      update is_ppa_localizador set
         cd_localizador          = p_cd_localizador,
         cd_regiao               = p_cd_regiao,
         cd_municipio            = p_cd_municipio,
         nome                    = p_nome,
         valor_total             = p_valor_total,
         valor_ano_anterior      = p_valor_ano_ant,
         qtd_ano_anterior        = p_qtd_ano_ant,
         valor_ano_corrente      = p_valor_ano_cor,
         qtd_ano_corrente        = p_qtd_ano_cor,
         reperc_financeira       = p_reperc_financ,
         valor_reperc_financeira = p_vr_reperc_financ,
         mes_inicio              = p_mes_ini,
         ano_inicio              = p_ano_ini,
         mes_termino             = p_mes_fim,
         ano_termino             = p_ano_fim,
         nome_alterado           = p_nome_alterado,
         observacao              = p_observacao,
         qtd_total               = p_qtd_total,
         cd_sof_referencia       = p_cd_sof_ref,
         flag_alteracao          = sysdate
       where cd_localizador_ppa = p_chave
         and cd_acao_ppa        = p_cd_acao_ppa
         and cd_programa        = p_cd_programa
         and cliente            = p_cliente
         and ano                = p_ano;
   End If;
end sp_PutXMLLocalizador_PPA;
/
