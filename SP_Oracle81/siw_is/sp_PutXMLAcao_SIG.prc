create or replace procedure sp_PutXMLAcao_SIG
   (p_cliente          in number   default null,
    p_ano              in number   default null,
    p_cd_programa      in varchar2 default null,
    p_cd_acao          in varchar2 default null,
    p_cd_subacao       in varchar2 default null,
    p_cd_localizador   in varchar2 default null,
    p_cd_regiao        in varchar2 default null,
    p_cd_acao_ppa      in varchar2 default null,
    p_tipo_acao        in number   default null,
    p_cd_produto       in number   default null,
    p_unidade_med      in number   default null,            
    p_unidade          in varchar2 default null,
    p_tipo_unid        in varchar2 default null,
    p_estagio          in varchar2 default null,
    p_andamento        in varchar2 default null,
    p_cronograma       in varchar2 default null,
    p_perc_execucao    in number   default null,
    p_desc_acao        in varchar2 default null,
    p_desc_subacao     in varchar2 default null,
    p_comentario       in varchar2 default null,
    p_direta           in varchar2 default null,
    p_descentralizada  in varchar2 default null,
    p_linha_credito    in varchar2 default null,
    p_cumulativa       in varchar2 default null,
    p_mes_ini          in varchar2 default null,
    p_ano_ini          in varchar2 default null,
    p_mes_fim          in varchar2 default null,
    p_ano_fim          in varchar2 default null,
    p_valor_ano_ant    in number   default null,
    p_coment_situacao  in varchar2 default null,
    p_situacao_atual   in varchar2 default null,
    p_result_obtidos   in varchar2 default null,
    p_mes_conc         in varchar2 default null,
    p_ano_conc         in varchar2 default null,
    p_coment_fisica    in varchar2 default null,
    p_coment_financ    in varchar2 default null,
    p_coment_fisica_bgu in varchar2 default null,
    p_coment_financ_bgu in varchar2 default null,
    p_restos_pagar     in varchar2 default null,
    p_coment_execucao  in varchar2 default null,
    p_coment_restos    in varchar2 default null,
    p_fiscal_segur     in varchar2 default null,
    p_estatais         in varchar2 default null,
    p_outras_fontes    in varchar2 default null,
    p_cd_sof_ref       in number   default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
   w_existe   number(5);
   w_sql      varchar2(2000);
   w_cd_subacao varchar2(4);

   cursor c_atualiza_acao is
      select 'update is_sig_acao '||
             '   set cd_acao_ppa = '||b.cd_acao_ppa||
             ' where ano         = '||p_ano||' '||
             '   and cliente     = '||p_cliente||' '||
             '   and cd_programa = '''||a.cd_programa||''' '||
             '   and cd_acao     = '''||a.cd_acao||''' '||
             '   and cd_unidade  = '''||a.cd_unidade||'''' w_sql
        from is_sig_acao a, is_ppa_acao b
       where a.cliente     = p_cliente
         and a.ano         = p_ano 
         and a.cliente     = b.cliente
         and a.ano         = b.ano
         and a.cd_programa = b.cd_programa
         and a.cd_acao     = b.cd_acao
         and a.cd_unidade  = b.cd_unidade;

   cursor c_atualiza_orgao is
      select 'update is_sig_acao '||
             '   set cd_orgao = '''||b.cd_orgao||''', '||
             '       cd_tipo_orgao = '''||b.cd_tipo_orgao||''''||
             ' where ano         = '||p_ano||' '||
             '   and cliente     = '||p_cliente||'  '||
             '   and cd_programa = '''||a.cd_programa||''' '||
             '   and cd_acao     = '''||a.cd_acao||''' '||
             '   and cd_subacao  = '''|| a.cd_subacao ||''' ' w_sql
        from is_sig_acao    a,
             is_sig_unidade b
       where a.cliente         = p_cliente
         and a.ano             = p_ano
         and a.ano             = b.ano
         and a.cd_unidade      = b.cd_unidade
         and a.cd_tipo_unidade = b.cd_tipo_unidade;
         
begin
   select count(*) into w_cont 
     from is_sig_acao a 
    where a.cd_localizador = p_cd_localizador
      and a.cd_acao        = p_cd_acao
      and a.cd_programa    = p_cd_programa 
      and a.cliente        = p_cliente
      and a.ano            = p_ano;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
   select count(*) into w_existe
     from is_ppa_localizador b
    where b.cd_localizador = p_cd_localizador;
   If w_operacao = 'I' and w_existe > 0 Then
      -- Insere registro
      insert into is_sig_acao (cliente, ano, cd_programa, cd_acao, cd_subacao, cd_localizador, cd_regiao, cd_acao_ppa,
                               cd_tipo_acao, cd_produto, cd_unidade_medida, cd_unidade, cd_tipo_unidade, cd_estagio,
                               cd_andamento, cd_cronograma, percentual_execucao, descricao_acao, descricao_subacao,
                               comentario, direta, descentralizada, linha_credito, meta_nao_cumulativa, mes_inicio,
                               ano_inicio, mes_termino, ano_termino, valor_ano_anterior, comentario_situacao, situacao_atual,
                               resultados_obtidos, mes_conclusao, ano_conclusao, comentario_fisica, comentario_financ,
                               comentario_fisica_bgu, comentario_financ_bgu, restos_pagar, comentario_execucao, comentario_restos_pagar, 
                               invest_fiscal_seguridade, invest_estatais, outras_fontes, cd_sof_referencia,
                               flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_cd_programa, p_cd_acao, p_cd_subacao, p_cd_localizador, p_cd_regiao, p_cd_acao_ppa,
              p_tipo_acao, p_cd_produto, p_unidade_med, p_unidade, p_tipo_unid, p_estagio,
              p_andamento, p_cronograma, p_perc_execucao, p_desc_acao, p_desc_subacao,
              p_comentario, p_direta, p_descentralizada, p_linha_credito, p_cumulativa, p_mes_ini,
              p_ano_ini, p_mes_fim, p_ano_fim, p_valor_ano_ant, p_coment_situacao, p_situacao_atual,
              p_result_obtidos, p_mes_conc, p_ano_conc, p_coment_fisica, p_coment_financ,
              p_coment_fisica_bgu, p_coment_financ_bgu, p_restos_pagar, p_coment_execucao, p_coment_restos,
              p_fiscal_segur, p_estatais, p_outras_fontes, p_cd_sof_ref,
              sysdate, sysdate);
      
      -- Atualiza o código da subação nos registros
      for crec in c_atualiza_acao loop
         EXECUTE IMMEDIATE crec.w_sql;
      end loop;

      -- Atualiza o código e o tipo do órgão da subação
      for crec in c_atualiza_orgao loop
         EXECUTE IMMEDIATE crec.w_sql;
      end loop;

   ElsIf w_operacao = 'A' and w_existe > 0 Then
      select cd_subacao into w_cd_subacao
        from is_sig_acao a 
       where a.cd_localizador = p_cd_localizador
         and a.cd_acao        = p_cd_acao
         and a.cd_programa    = p_cd_programa 
         and a.cliente        = p_cliente
         and a.ano            = p_ano;
      -- Altera registro
      update is_sig_acao set
         cd_regiao               = p_cd_regiao, 
         cd_tipo_acao            = p_tipo_acao,
         cd_produto              = p_cd_produto, 
         cd_unidade_medida       = p_unidade_med,         
         cd_unidade              = p_unidade,
         cd_tipo_unidade         = p_tipo_unid,
         cd_estagio              = p_estagio,
         cd_andamento            = p_andamento,
         cd_cronograma           = p_cronograma,
         percentual_execucao     = p_perc_execucao, 
         descricao_acao          = p_desc_acao,
         descricao_subacao       = p_desc_subacao,
         comentario              = p_comentario,
         direta                  = p_direta,
         descentralizada         = p_descentralizada,
         linha_credito           = p_linha_credito,
         meta_nao_cumulativa     = p_cumulativa,
         mes_inicio              = p_mes_ini,
         ano_inicio              = p_ano_ini,
         mes_termino             = p_mes_fim,
         ano_termino             = p_ano_fim,
         valor_ano_anterior      = p_valor_ano_ant,
         comentario_situacao     = p_coment_situacao,
         situacao_atual          = p_situacao_atual,
         resultados_obtidos      = p_result_obtidos,
         mes_conclusao           = p_mes_conc,
         ano_conclusao           = p_ano_conc,
         comentario_fisica       = p_coment_fisica,
         comentario_financ       = p_coment_financ,
         comentario_fisica_bgu   = p_coment_fisica_bgu,
         comentario_financ_bgu   = p_coment_financ_bgu,
         restos_pagar            = p_restos_pagar,
         comentario_execucao     = p_coment_execucao,
         comentario_restos_pagar = p_coment_restos,
         invest_fiscal_seguridade = p_fiscal_segur,
         invest_estatais         = p_estatais,
         outras_fontes           = p_outras_fontes,
         cd_sof_referencia       = p_cd_sof_ref,
         flag_alteracao          = sysdate
       where cd_localizador   = p_cd_localizador
         and cd_acao          = p_cd_acao
         and cd_programa      = p_cd_programa
         and cliente          = p_cliente
         and ano              = p_ano;
      If w_cd_subacao <> p_cd_subacao or p_cd_subacao is null Then
         EXECUTE IMMEDIATE 'alter table is_acao disable constraint FK_ISACA_ISSIGACA';
         EXECUTE IMMEDIATE 'alter table is_acao_financ disable constraint FK_ISACAFIN_ISSIGACA';
         EXECUTE IMMEDIATE 'alter table is_sig_dado_financeiro disable constraint FK_ISSIGDADFIN_ISSIGACA';
         EXECUTE IMMEDIATE 'alter table is_sig_dado_fisico disable constraint FK_ISSIGDADFIS_ISSIGACA';
         EXECUTE IMMEDIATE 'alter table is_sig_restricao_acao disable constraint FK_ISSIGRESACA_ISSIGACA';
         update is_sig_acao set cd_subacao = p_cd_subacao where ano = p_ano and cd_programa = p_cd_programa and cliente = p_cliente and cd_acao = p_cd_acao and cd_subacao = w_cd_subacao;
         update is_acao set cd_subacao = p_cd_subacao where ano = p_ano and cd_programa = p_cd_programa and cliente = p_cliente and cd_acao = p_cd_acao and cd_subacao = w_cd_subacao;
         update is_acao_financ set cd_subacao = p_cd_subacao where ano = p_ano and cd_programa = p_cd_programa and cliente = p_cliente and cd_acao = p_cd_acao and cd_subacao = w_cd_subacao;
         update is_sig_dado_financeiro set cd_subacao = p_cd_subacao where ano = p_ano and cd_programa = p_cd_programa and cliente = p_cliente and cd_acao = p_cd_acao and cd_subacao = w_cd_subacao;
         update is_sig_dado_fisico set cd_subacao = p_cd_subacao where ano = p_ano and cd_programa = p_cd_programa and cliente = p_cliente and cd_acao = p_cd_acao and cd_subacao = w_cd_subacao;
         update is_sig_restricao_acao set cd_subacao = p_cd_subacao where ano = p_ano and cd_programa = p_cd_programa and cliente = p_cliente and cd_acao = p_cd_acao and cd_subacao = w_cd_subacao;
         EXECUTE IMMEDIATE 'alter table is_acao enable constraint FK_ISACA_ISSIGACA';
         EXECUTE IMMEDIATE 'alter table is_acao_financ enable constraint FK_ISACAFIN_ISSIGACA';
         EXECUTE IMMEDIATE 'alter table is_sig_dado_financeiro enable constraint FK_ISSIGDADFIN_ISSIGACA';
         EXECUTE IMMEDIATE 'alter table is_sig_dado_fisico enable constraint FK_ISSIGDADFIS_ISSIGACA';
         EXECUTE IMMEDIATE 'alter table is_sig_restricao_acao enable constraint FK_ISSIGRESACA_ISSIGACA';
      End If;
   End If;
end sp_PutXMLAcao_SIG;
/
