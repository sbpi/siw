create or replace procedure sp_PutXMLAcao_PPA
   (p_cliente          in number   default null,
    p_ano              in number   default null,
    p_cd_programa      in varchar2 default null,
    p_chave            in varchar2 default null,
    p_cd_acao          in varchar2 default null,
    p_unidade          in varchar2 default null,
    p_tipo_unid        in varchar2 default null,
    p_funcao           in varchar2 default null,
    p_subfuncao        in varchar2 default null,
    p_tipo_acao        in number   default null,
    p_cd_produto       in number   default null,
    p_ds_produto       in varchar2 default null,
    p_unidade_med      in number   default null,
    p_tipo_inclusao    in number   default null,
    p_cd_esfera        in number   default null,
    p_orgao_siorg      in number   default null,
    p_nome             in varchar2 default null,
    p_finalidade       in varchar2 default null,
    p_descricao        in varchar2 default null,
    p_base_legal       in varchar2 default null,
    p_reperc_financ    in varchar2 default null,
    p_vr_reperc_financ in number   default null,
    p_padronizada      in varchar2 default null,
    p_set_padronizada  in varchar2 default null,
    p_direta           in varchar2 default null,
    p_descentralizada  in varchar2 default null,
    p_linha_credito    in varchar2 default null,
    p_transf_obrig     in varchar2 default null,
    p_transf_vol       in varchar2 default null,
    p_transf_outras    in varchar2 default null,
    p_despesa_obrig    in varchar2 default null,
    p_bloqueio_prog    in varchar2 default null,
    p_detalhamento     in varchar2 default null,
    p_mes_ini          in varchar2 default null,
    p_ano_ini          in varchar2 default null,
    p_mes_fim          in varchar2 default null,
    p_ano_fim          in varchar2 default null,
    p_valor_total      in number   default null,
    p_valor_ano_ant    in number   default null,
    p_qtd_ano_ant      in number   default null,
    p_valor_ano_cor    in number   default null,
    p_qtd_ano_cor      in number   default null,
    p_ordem_pri        in number   default null,
    p_observacao       in varchar2 default null,
    p_cd_sof           in varchar2 default null,        
    p_qtd_total        in number   default null,
    p_cd_sof_ref       in number   default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);

begin
   select count(*) into w_cont 
     from is_ppa_acao a 
    where a.cd_acao_ppa = p_chave
      and a.cd_programa = p_cd_programa 
      and a.cliente     = p_cliente
      and a.ano         = p_ano;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
      
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_ppa_acao (cliente, ano, cd_programa, cd_acao_ppa, cd_unidade, cd_tipo_unidade, cd_funcao, cd_subfuncao,
                               cd_tipo_acao, cd_produto, cd_unidade_medida, cd_tipo_inclusao, cd_esfera, cd_orgao_siorg, 
                               cd_acao, produto, nome, finalidade, descricao, base_legal, reperc_financeira, valor_reperc_financeira,
                               padronizada, set_padronizada, direta, descentralizada, linha_credito, transf_obrigatoria, 
                               transf_voluntaria, transf_outras, despesa_obrigatoria, bloqueio_programacao, detalhamento,
                               mes_inicio, ano_inicio, mes_termino, ano_termino, valor_total, valor_ano_anterior, qtd_ano_anterior,
                               valor_ano_corrente, qtd_ano_corrente, ordem_prioridade, observacao, cd_sof, qtd_total, cd_sof_referencia, 
                               flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_cd_programa, p_chave, p_unidade, p_tipo_unid, p_funcao, p_subfuncao, 
              p_tipo_acao, p_cd_produto, p_unidade_med, p_tipo_inclusao, p_cd_esfera, p_orgao_siorg,
              p_cd_acao, p_ds_produto, p_nome, p_finalidade, p_descricao, p_base_legal, p_reperc_financ, p_vr_reperc_financ,
              Nvl(p_padronizada,'S'), Nvl(p_set_padronizada,'S'), Nvl(p_direta,'S'), Nvl(p_descentralizada,'S'), Nvl(p_linha_credito,'S'), Nvl(p_transf_obrig,'S'),
              Nvl(p_transf_vol,'S'), Nvl(p_transf_outras,'S'), Nvl(p_despesa_obrig,'S'), Nvl(p_bloqueio_prog,'S'), p_detalhamento,
              p_mes_ini, p_ano_ini, p_mes_fim, p_ano_fim, p_valor_total, p_valor_ano_ant, p_qtd_ano_ant, 
              p_valor_ano_cor, p_qtd_ano_cor, p_ordem_pri, p_observacao, p_cd_sof, p_qtd_total, p_cd_sof_ref,
              sysdate, sysdate);
   Else
      -- Altera registro
      update is_ppa_acao set
         cd_unidade              = p_unidade,
         cd_tipo_unidade         = p_tipo_unid,
         cd_funcao               = p_funcao,
         cd_subfuncao            = p_subfuncao,
         cd_tipo_acao            = p_tipo_acao,
         cd_produto              = p_cd_produto, 
         cd_unidade_medida       = p_unidade_med,
         cd_tipo_inclusao        = p_tipo_inclusao,
         cd_esfera               = p_cd_esfera,
         cd_orgao_siorg          = p_orgao_siorg,
         cd_acao                 = p_cd_acao,
         produto                 = p_ds_produto,
         nome                    = p_nome,
         finalidade              = p_finalidade,
         descricao               = p_descricao,
         base_legal              = p_base_legal,
         reperc_financeira       = p_reperc_financ,
         valor_reperc_financeira = p_vr_reperc_financ,
         padronizada             = Nvl(p_padronizada,'S'),
         set_padronizada         = Nvl(p_set_padronizada,'S'),
         direta                  = Nvl(p_direta,'S'),
         descentralizada         = Nvl(p_descentralizada,'S'),
         linha_credito           = Nvl(p_linha_credito,'S'),
         transf_obrigatoria      = Nvl(p_transf_obrig,'S'),
         transf_voluntaria       = Nvl(p_transf_vol,'S'),
         transf_outras           = Nvl(p_transf_outras,'S'),
         despesa_obrigatoria     = Nvl(p_despesa_obrig,'S'),
         bloqueio_programacao    = Nvl(p_bloqueio_prog,'S'),
         detalhamento            = p_detalhamento,
         mes_inicio              = p_mes_ini,
         ano_inicio              = p_ano_ini,
         mes_termino             = p_mes_fim,
         ano_termino             = p_ano_fim,
         valor_total             = p_valor_total,
         valor_ano_anterior      = p_valor_ano_ant,
         qtd_ano_anterior        = p_qtd_ano_ant,
         valor_ano_corrente      = p_valor_ano_cor,
         qtd_ano_corrente        = p_qtd_ano_cor,
         ordem_prioridade        = p_ordem_pri,
         observacao              = p_observacao,
         cd_sof                  = p_cd_sof,
         qtd_total               = p_qtd_total,
         cd_sof_referencia       = p_cd_sof_ref,
         flag_alteracao     = sysdate
       where cd_acao_ppa      = p_chave
         and cd_programa      = p_cd_programa
         and cliente          = p_cliente
         and ano              = p_ano;
   End If;
end sp_PutXMLAcao_PPA;
/
