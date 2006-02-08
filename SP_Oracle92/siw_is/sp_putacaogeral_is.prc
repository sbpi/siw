create or replace procedure SP_PutAcaoGeral_IS
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_copia               in  number   default null,
    p_menu                in number,
    p_unidade             in number    default null,
    p_solicitante         in number    default null,
    p_proponente          in varchar2  default null,
    p_cadastrador         in number    default null,
    p_executor            in number    default null,
    p_descricao           in varchar2  default null,
    p_justificativa       in varchar2  default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_valor               in number    default null,
    p_data_hora           in varchar2  default null,
    p_unid_resp           in number    default null,
    p_titulo              in varchar2  default null,
    p_prioridade          in number    default null,
    p_aviso               in varchar2  default null,
    p_dias                in number    default null,
    p_cidade              in number    default null,
    p_palavra_chave       in varchar2  default null,
    p_inicio_real         in date      default null,
    p_fim_real            in date      default null,
    p_concluida           in varchar2  default null,
    p_data_conclusao      in date      default null,
    p_nota_conclusao      in varchar2  default null,
    p_custo_real          in number    default null,
    p_opiniao             in number    default null,
    p_ano                 in number    default null,
    p_programa            in varchar2  default null,
    p_cliente             in number    default null,
    p_acao                in varchar2  default null,
    p_subacao             in varchar2  default null,
    p_cd_unidade          in varchar2  default null,
    p_sq_isprojeto        in number    default null,
    p_selecao_mp          in varchar2  default null,
    p_selecao_se          in varchar2  default null,
    p_sq_natureza         in number    default null,
    p_sq_horizonte        in number    default null,
    p_unidade_adm         in number    default null,
    p_ln_programa         in varchar2  default null,
    p_chave_nova          out number
   ) is
   w_chave   number(18);
   w_chave1  number(18);
   w_log_sol number(18);
   w_log_esp number(18);
   w_ativ    number(18);
   w_arq     varchar2(4000) := ', ';
   i         number(10) := 0;
      --w_referencia number(2) := to_number(sysdate,'aaaa') - 2003;
    
   type rec_etapa is record (
       sq_chave_destino       number(10) := null,
       sq_chave_origem        number(10) := null,
       sq_chave_pai_origem    number(10) := null
      );
   type tb_etapa is table of rec_etapa index by binary_integer;
   type tb_etapa_pai is table of number(10) index by binary_integer;
    
   w_etapa     tb_etapa;
   w_etapa_pai tb_etapa_pai;
     
   cursor c_etapas is
      select * from is_meta where sq_siw_solicitacao = p_copia;

   cursor c_atividades is
      select * from siw.siw_solicitacao t where t.sq_solic_pai = p_chave;
   
   cursor c_arquivos is
      select t.sq_siw_arquivo from siw.siw_solic_arquivo t where t.sq_siw_solicitacao = p_chave;
   
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select siw.sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw.siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante, 
         cadastrador,        executor,      descricao,           justificativa,      
         inicio,             fim,           inclusao,            ultima_alteracao, 
         conclusao,          valor,         opiniao,             data_hora, 
         sq_unidade,         sq_cidade_origem,    palavra_chave, ano)
      (select 
         w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_executor,    p_descricao,         p_justificativa,
         p_inicio,           p_fim,         sysdate,             sysdate,
         null,               p_valor,       null,                p_data_hora,
         p_unidade,          p_cidade,      p_palavra_chave,     p_ano
         from siw.siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Insere registro em pj_projeto
      Insert into siw.pj_projeto
         ( sq_siw_solicitacao,  sq_unidade_resp, titulo,            prioridade,
           aviso_prox_conc,     dias_aviso,      inicio_real,       fim_real,
           concluida,           data_conclusao,  nota_conclusao,    custo_real,
           proponente
         )
      (select
           w_chave,             p_unid_resp,     p_titulo,          p_prioridade,
           p_aviso,             p_dias,          null,              null,
           'N',                 null,            null,              0,
           p_proponente
        from dual
      );
      
      -- Grava os dados de uma ação orçamentária, se for o caso
      If p_acao is not null or p_sq_isprojeto is not null Then
         -- Grava os dados complementares ao projeto, relativos à ação orçamentária
         insert into is_acao  (sq_siw_solicitacao, ano, cd_programa, cliente, cd_acao, cd_subacao, sq_isprojeto, selecao_mp, selecao_se, sq_unidade, cd_unidade)
         values (w_chave, p_ano, p_programa, p_cliente, p_acao, p_subacao, p_sq_isprojeto, p_selecao_mp, p_selecao_se, p_unidade_adm, p_cd_unidade);
         -- Grava as metas do PPA por localizador da ação na tabela de metas do módulo infra-sig(IS_META) 
         insert into is_meta (sq_meta, sq_siw_solicitacao, titulo, descricao, ordem, inicio_previsto, 
                              fim_previsto, quantidade, cumulativa, qtd_ano_1,
                              qtd_ano_2, qtd_ano_3, qtd_ano_4, qtd_ano_5, qtd_ano_6, unidade_medida,
                              cd_subacao)
         (select sq_meta.nextval, w_chave, b.nome|| ' - ' ||g.nome, ' ', 0, p_inicio, 
                 p_fim, Nvl(f.previsao_ano,0), case a.meta_nao_cumulativa when 'N' then 'S' else 'N' end, Nvl(d.qtd_ano_1,0),
                 Nvl(d.qtd_ano_2,0), Nvl(d.qtd_ano_3,0), Nvl(d.qtd_ano_4,0), Nvl(d.qtd_ano_5,0), Nvl(d.qtd_ano_6,0), e.nome,
                 a.cd_subacao
            from is_sig_acao                        a
                 left outer join is_sig_produto     b on (a.cd_produto         = b.cd_produto)
                 left outer join is_ppa_localizador c on (a.cd_programa        = c.cd_programa        and
                                                          a.cd_acao_ppa        = c.cd_acao_ppa        and
                                                          a.cd_localizador     = c.cd_localizador     and
                                                          a.cliente            = c.cliente            and
                                                          a.ano                = c.ano)
                 left outer join is_ppa_dado_fisico d on (c.cd_programa        = d.cd_programa        and
                                                          c.cd_acao_ppa        = d.cd_acao_ppa        and 
                                                          c.cd_localizador_ppa = d.cd_localizador_ppa and
                                                          c.cliente            = d.cliente            and
                                                          c.ano                = d.ano)
                inner      join is_sig_unidade_medida e on (a.cd_unidade_medida = e.cd_unidade_medida)
                left outer join is_sig_dado_fisico    f on (a.cd_programa       = f.cd_programa       and
                                                            a.cd_acao           = f.cd_acao           and
                                                            a.cd_subacao        = f.cd_subacao        and
                                                            a.cliente           = f.cliente           and
                                                            a.ano               = f.ano) 
                inner      join is_regiao             g on (a.cd_regiao         = g.cd_regiao)
          where a.cd_programa = p_programa
            and a.cd_acao     = p_acao
            and a.cd_unidade  = p_cd_unidade
            and a.cliente     = p_cliente
            and a.ano         = p_ano);
         -- Grava as restrições da ação PPA na tabela de restrições do módulo infra-sig(IS_RESTRICAO) 
         insert into is_restricao (sq_restricao, sq_acao, sq_projeto, cd_tipo_restricao,
                                   cd_tipo_inclusao, cd_competencia, inclusao, descricao, providencia,
                                   superacao, relatorio, tempo_habil, observacao_monitor, observacao_controle)
         (select sq_restricao.nextval, w_chave, p_sq_isprojeto, a.cd_tipo_restricao,
                 a.cd_tipo_inclusao, a.cd_competencia, a.inclusao, a.descricao, a.providencia,
                 a.superacao, a.relatorio, a.tempo_habil, a.observacao_monitor, a.observacao_controle
            from is_sig_restricao_acao a
           where a.cd_programa = p_programa
             and a.cd_acao     = p_acao
             and a.cliente     = p_cliente
             and a.ano         = p_ano);
      Elsif p_programa is not null and p_acao is null Then
         -- Grava os dados complementares ao projeto, relativos ao programa orçamentário
         insert into is_programa  (sq_siw_solicitacao, ano, cd_programa, cliente, sq_natureza, sq_horizonte, selecao_mp, selecao_se, sq_unidade)
         values (w_chave, p_ano, p_programa, p_cliente, p_sq_natureza, p_sq_horizonte, p_selecao_mp, p_selecao_se, p_unidade_adm); 
         -- Grava os indicadores PPA(IS_SIG_INDICADOR) do programa na tabela de indicadores do módulo infra-sig(IS_INDICADOR) 
         insert into is_indicador (sq_indicador, sq_siw_solicitacao, ano, cd_programa, is_cliente,
                                   is_ano, is_cd_programa, cliente, cd_indicador, cd_unidade_medida,
                                   cd_periodicidade, cd_base_geografica, titulo, fonte, formula,
                                   valor_referencia, apuracao_referencia, previsao_ano_1, 
                                   previsao_ano_2, previsao_ano_3, previsao_ano_4, observacao, ordem, quantidade)  
         (select sq_indicador.nextval, w_chave, a.ano, a.cd_programa, a.cliente, a.ano,a.cd_programa, 
                 a.cliente, a.cd_indicador, a.cd_unidade_medida, a.cd_periodicidade,  
                 a.cd_base_geografica, a.nome, a.fonte, a.formula, Nvl(a.valor_apurado,0), 
                 b.apuracao, Nvl(b.valor_ano_1,0), Nvl(b.valor_ano_2,0), Nvl(b.valor_ano_3,0), Nvl(b.valor_ano_4,0), 
                 b.observacao, 0, Nvl(b.valor_ano_2,0)            
            from is_sig_indicador a
                 inner join is_ppa_indicador b on (a.cliente      = b.cliente     and
                                                   a.ano          = b.ano         and
                                                   a.cd_programa  = b.cd_programa and
                                                   a.cd_indicador = b.cd_indicador)
           where a.cd_programa = p_programa
             and a.ano         = p_ano
             and a.cliente     = p_cliente);
         -- Grava o endereço internet do programa, se houver
         If p_ln_programa is not null Then
            update is_sig_programa set
               ln_programa = p_ln_programa
            where cd_programa = p_programa;
         End If;
         -- Grava as restrições do programa PPA na tabela de restrições do módulo infra-sig(IS_RESTRICAO) 
        insert into is_restricao (sq_restricao, sq_programa, cd_tipo_restricao,
                                   cd_tipo_inclusao, cd_competencia, inclusao, descricao, providencia,
                                   superacao, relatorio, tempo_habil, observacao_monitor, observacao_controle)
         (select sq_restricao.nextval, w_chave, a.cd_tipo_restricao, a.cd_tipo_inclusao, 
                 a.cd_competencia, a.inclusao, a.descricao, a.providencia, a.superacao, a.relatorio, 
                 a.tempo_habil, a.observacao_monitor, a.observacao_controle
            from is_sig_restricao_programa a
           where a.cd_programa = p_programa
             and a.cliente     = p_cliente
             and a.ano         = p_ano);
      End If;

      -- Insere log da solicitação
      Insert Into siw.siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          siw.sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          sysdate,            'N',
          'Cadastramento inicial'
         from siw.siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
           
      -- Se o projeto foi copiado de outra, grava os dados complementares
      If p_copia is not null Then
         -- Insere registro na tabela de interessados
         Insert Into siw.pj_projeto_interes 
            ( sq_pessoa,   sq_siw_solicitacao,   tipo_visao,    envia_email )
         (Select
              a.sq_pessoa, w_chave,              a.tipo_visao,  a.envia_email 
           from siw.pj_projeto_interes a
          where a.sq_siw_solicitacao = p_copia
         );
          -- Insere etapas do projeto
          for crec in c_etapas loop

             -- recupera a próxima chave do recurso
             select siw.sq_projeto_etapa.nextval into w_chave1 from dual;

             -- Guarda pai do registro original
             i := i + 1;
             w_etapa(i).sq_chave_destino    := w_chave1;
             w_etapa(i).sq_chave_origem     := crec.sq_meta;
             --w_etapa(i).sq_chave_pai_origem := crec.sq_etapa_pai;
             
             w_etapa_pai(crec.sq_meta) := w_chave1;
        
             -- insere a meta
             Insert Into is_meta
                ( sq_meta,            sq_siw_solicitacao,       ordem,              titulo, 
                  descricao,          inicio_previsto,          fim_previsto,       inicio_real,
                  fim_real,           perc_conclusao,           orcamento )
             Values
                ( w_chave1,           w_chave,                  crec.ordem,         crec.titulo,
                  crec.descricao,     crec.inicio_previsto,     crec.fim_previsto,  crec.inicio_real,
                  crec.fim_real,      crec.perc_conclusao,      crec.orcamento);

             -- Grava os dados de uma ação orçamentária, se for o caso
             If p_acao is not null or p_sq_isprojeto is not null Then
                -- Grava os dados complementares ao projeto, relativos à ação orçamentária
                insert into is_acao (sq_siw_solicitacao, ano, cd_programa, cliente, cd_acao, cd_subacao, sq_isprojeto, selecao_mp, selecao_se, sq_unidade, cd_unidade)
                values (p_copia, p_ano, p_programa, p_cliente, p_acao, p_subacao, p_sq_isprojeto, p_selecao_mp, p_selecao_se, p_unidade_adm, p_cd_unidade);
                -- Grava as metas do PPA por localizador da ação na tabela de metas do módulo infra-sig(IS_META) 
                insert into is_meta (sq_meta, sq_siw_solicitacao, titulo, descricao, ordem, inicio_previsto,
                                     fim_previsto, quantidade, cumulativa, qtd_ano_1,
                                     qtd_ano_2, qtd_ano_3, qtd_ano_4, qtd_ano_5, qtd_ano_6, unidade_medida,
                                     cd_subacao)
                (select sq_meta.nextval, w_chave, b.nome|| ' - ' ||g.nome, ' ', 0, p_inicio, 
                        p_fim, Nvl(f.previsao_ano,0), case a.meta_nao_cumulativa when 'N' then 'S' else 'N'end, Nvl(d.qtd_ano_1,0),
                        Nvl(d.qtd_ano_2,0), Nvl(d.qtd_ano_3,0), Nvl(d.qtd_ano_4,0), Nvl(d.qtd_ano_5,0), Nvl(d.qtd_ano_6,0), e.nome,                        a.cd_subacao
                   from is_sig_acao                        a
                        left outer join is_sig_produto     b on (a.cd_produto         = b.cd_produto)
                        left outer join is_ppa_localizador c on (a.cd_programa        = c.cd_programa        and
                                                                 a.cd_acao_ppa        = c.cd_acao_ppa        and
                                                                 a.cd_localizador     = c.cd_localizador     and
                                                                 a.cliente            = c.cliente            and
                                                                 a.ano                = c.ano)
                        left outer join is_ppa_dado_fisico d on (c.cd_programa        = d.cd_programa        and
                                                                 c.cd_acao_ppa        = d.cd_acao_ppa        and 
                                                                 c.cd_localizador_ppa = d.cd_localizador_ppa and
                                                                 c.cliente            = d.cliente            and
                                                                 c.ano                = d.ano)
                        inner      join is_sig_unidade_medida e on (a.cd_unidade_medida = e.cd_unidade_medida)
                        left outer join is_sig_dado_fisico    f on (a.cd_programa       = f.cd_programa      and
                                                                    a.cd_acao           = f.cd_acao          and
                                                                    a.cd_subacao        = f.cd_subacao       and
                                                                    a.cliente           = f.cliente          and
                                                                    a.ano               = f.ano)
                        inner      join is_regiao             g on (a.cd_regiao         = g.cd_regiao)
                  where a.cd_programa = p_programa
                    and a.cd_acao     = p_acao
                    and a.cd_unidade  = p_cd_unidade
                    and a.cliente     = p_cliente
                    and a.ano         = p_ano);
                -- Grava as restrições da ação PPA na tabela de restrições do módulo infra-sig(IS_RESTRICAO) 
               insert into is_restricao (sq_restricao, sq_acao, sq_projeto, cd_tipo_restricao,
                                          cd_tipo_inclusao, cd_competencia, inclusao, descricao, providencia,
                                          superacao, relatorio, tempo_habil, observacao_monitor, observacao_controle)
                (select sq_restricao.nextval, w_chave, p_sq_isprojeto, a.cd_tipo_restricao,
                        a.cd_tipo_inclusao, a.cd_competencia, a.inclusao, a.descricao, a.providencia,
                        a.superacao, a.relatorio, a.tempo_habil, a.observacao_monitor, a.observacao_controle
                   from is_sig_restricao_acao a
                  where a.cd_programa = p_programa
                    and a.cd_acao     = p_acao
                    and a.cliente     = p_cliente
                    and a.ano         = p_ano);
             Elsif p_programa is not null and p_acao is null Then
                -- Grava os dados complementares ao projeto, relativos ao programa orçamentário
                insert into is_programa  (sq_siw_solicitacao, ano, cd_programa, cliente, sq_natureza, sq_horizonte, selecao_mp, selecao_se, sq_unidade)
                values (w_chave, p_ano, p_programa, p_cliente, p_sq_natureza, p_sq_horizonte, p_selecao_mp, p_selecao_se, p_unidade_adm); 
                -- Grava os indicadores PPA(IS_SIG_INDICADOR) do programa na tabela de indicadores do módulo infra-sig(IS_INDICADOR) 
                insert into is_indicador (sq_indicador, sq_siw_solicitacao, ano, cd_programa, is_cliente,
                                          is_ano, is_cd_programa, cliente, cd_indicador, cd_unidade_medida,
                                          cd_periodicidade, cd_base_geografica, titulo, fonte, formula,
                                          valor_referencia, apuracao_referencia, previsao_ano_1, 
                                          previsao_ano_2, previsao_ano_3, previsao_ano_4, observacao, ordem, quantidade)  
                (select sq_indicador.nextval, w_chave, a.ano, a.cd_programa, a.cliente, a.ano,a.cd_programa, 
                        a.cliente, a.cd_indicador, a.cd_unidade_medida, a.cd_periodicidade,  
                        a.cd_base_geografica, a.nome, a.fonte, a.formula, Nvl(a.valor_apurado,0), 
                        b.apuracao, Nvl(b.valor_ano_1,0), Nvl(b.valor_ano_2,0), Nvl(b.valor_ano_3,0), Nvl(b.valor_ano_4,0), 
                        b.observacao, 0, Nvl(b.valor_ano_2,0)
                   from is_sig_indicador a
                        inner join is_ppa_indicador b on (a.cliente      = b.cliente     and
                                                          a.ano          = b.ano         and
                                                          a.cd_programa  = b.cd_programa and
                                                          a.cd_indicador = b.cd_indicador)
                  where a.cd_programa = p_programa
                    and a.ano         = p_ano
                    and a.cliente     = p_cliente);
                -- Grava o endereço internet do programa, se houver
                If p_ln_programa is not null Then
                   update is_sig_programa set
                          ln_programa = p_ln_programa
                   where cd_programa = p_programa;
             End If;
             -- Grava as restrições do programa PPA na tabela de restrições do módulo infra-sig(IS_RESTRICAO) 
           insert into is_restricao (sq_restricao, sq_programa, cd_tipo_restricao,
                                       cd_tipo_inclusao, cd_competencia, inclusao, descricao, providencia,
                                       superacao, relatorio, tempo_habil, observacao_monitor, observacao_controle)
             (select sq_restricao.nextval, w_chave, a.cd_tipo_restricao, a.cd_tipo_inclusao,
                     a.cd_competencia, a.inclusao, a.descricao, a.providencia, a.superacao, a.relatorio,
                     a.tempo_habil, a.observacao_monitor, a.observacao_controle
                from is_sig_restricao_programa a
               where a.cd_programa = p_programa
                 and a.cliente     = p_cliente
                 and a.ano         = p_ano);
          End If;

          end loop;

          -- Acerta o vínculo entre as etapas
          --i := 0;
          --for i in 1 .. w_etapa.Count loop
          --    if w_etapa(i).sq_chave_pai_origem is not null then
          --       update pj_projeto_etapa a
          --          set sq_etapa_pai = w_etapa_pai(w_etapa(i).sq_chave_pai_origem)
          --        where sq_projeto_etapa = w_etapa(i).sq_chave_destino;
          --    end if;
          --end loop;
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw.siw_solicitacao set
          descricao        = p_descricao,
          justificativa    = p_justificativa,
          solicitante      = p_solicitante,
          executor         = p_executor,
          inicio           = p_inicio,
          fim              = p_fim,
          ultima_alteracao = sysdate,
          valor            = p_valor,
          sq_cidade_origem = p_cidade,
          palavra_chave    = p_palavra_chave
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de projetos
      Update siw.pj_projeto set
          sq_unidade_resp = p_unid_resp,
          proponente       = p_proponente,
          titulo           = trim(p_titulo),
          prioridade      = p_prioridade,
          aviso_prox_conc = p_aviso,
          dias_aviso      = p_dias,
          inicio_real     = p_inicio_real
      where sq_siw_solicitacao = p_chave;

      -- Atualiza os dados de uma ação orçamentária, se for o caso
      If p_acao is not null or p_sq_isprojeto is not null Then
         -- Grava os dados complementares ao projeto, relativos à ação orçamentária
         update is_acao set
            selecao_mp       = p_selecao_mp,
            selecao_se       = p_selecao_se,
            sq_unidade       = p_unidade_adm,
            sq_isprojeto     = p_sq_isprojeto
         where sq_siw_solicitacao = p_chave;
      Elsif p_programa is not null and p_acao is null Then
         update is_programa set
            sq_natureza      = p_sq_natureza,
            sq_horizonte     = p_sq_horizonte,
            selecao_mp       = p_selecao_mp,
            selecao_se       = p_selecao_se,
            sq_unidade       = p_unidade_adm
         where sq_siw_solicitacao = p_chave;
         
         update is_sig_programa set
            ln_programa      = p_ln_programa
         where cd_programa = p_programa;
      End If;

   Elsif p_operacao = 'E' Then -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw.siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from siw.pj_projeto_log where sq_siw_solicitacao = p_chave;
      select count(*) into w_ativ    from siw.siw_solicitacao where sq_solic_pai      = p_chave;
      
      -- Se não tem atividades vinculadas nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (w_log_sol + w_log_esp + w_ativ) > 1 Then
         -- Insere log de cancelamento
         Insert Into siw.siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             siw.sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          sysdate,              'N',
             'Cancelamento'
            from siw.siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
         );
         
         -- Atualiza a situação do projeto
         update siw.pj_projeto set concluida = 'S' where sq_siw_solicitacao = p_chave;

         -- Recupera a chave que indica que a solicitação está cancelada
         select a.sq_siw_tramite into w_chave from siw.siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situação da solicitação
         update siw.siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = p_chave;
         
         -- Atualiza a ação PPA e inicitiva prioritária quando a ação for cancelada ou excluída
         --update is_acao set ano = null, cd_programa = null, cd_acao = null, cd_subacao = null, sq_isprojeto = null where sq_siw_solicitacao = p_chave;
         
         -- Atualiza o programa PPA quando o programa for cancelada ou excluída
         --update is_programa set ano = null, cd_programa = null where sq_siw_solicitacao = p_chave;

         -- Atualiza eventuais atividades ligadas ao projeto
         for crec in c_atividades loop
             -- Insere log de cancelamento
             Insert Into siw.siw_solic_log 
                (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
                 sq_siw_tramite,            data,                 devolucao, 
                 observacao
                )
             (select 
                 siw.sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
                 a.sq_siw_tramite,          sysdate,              'N',
                 'Cancelamento'
                from siw.siw_solicitacao a
               where a.sq_siw_solicitacao = crec.sq_siw_solicitacao
             );
             
             -- Atualiza a situação do projeto
             update siw.gd_demanda set concluida = 'S' where sq_siw_solicitacao = crec.sq_siw_solicitacao;
    
             -- Recupera a chave que indica que a solicitação está cancelada
             select a.sq_siw_tramite into w_chave from siw.siw_tramite a where a.sq_menu = crec.sq_menu and a.sigla = 'CA';
             
             -- Atualiza a situação da solicitação
             update siw.siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = crec.sq_siw_solicitacao;
         end loop;
      Else
         
         delete siw.pj_projeto_interes where sq_siw_solicitacao = p_chave;
         
         -- Remove os resgistros ligados à ação
         delete is_sig_restricao_acao where cd_programa = p_programa and cd_acao = p_acao and cd_subacao = p_subacao and cliente = p_cliente and ano = p_ano;
         delete is_acao_financ where sq_siw_solicitacao = p_chave;
         delete is_meta        where sq_siw_solicitacao = p_chave;
         delete is_acao        where sq_siw_solicitacao = p_chave;
         
         -- Remove os registros ligados ao programa
         delete is_sig_restricao_programa where cd_programa = p_programa and cliente = p_cliente and ano = p_ano;
         
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));
         
         delete siw.siw_solic_arquivo         where sq_siw_solicitacao = p_chave;
         delete siw.siw_arquivo               where sq_siw_arquivo     in (w_arq);
         
         -- Remove os arquivos ligados à solicitação informada
         --delete from
         --(select * 
         --  from siw_solic_arquivo      a 
         --       inner join siw_arquivo b on (a.sq_siw_arquivo = b.sq_siw_arquivo)
         -- where a.sq_siw_solicitacao = p_chave);
         
         delete is_indicador              where sq_siw_solicitacao = p_chave;
         delete is_programa               where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de projetos
         delete siw.pj_projeto where sq_siw_solicitacao = p_chave;
            
         -- Remove o log da solicitação
         delete siw.siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw.siw_solicitacao where sq_siw_solicitacao = p_chave;
         
         -- Remove os registros vinculados ao projeto
         delete is_projeto where sq_isprojeto       in (select sq_isprojeto from is_acao where sq_siw_solicitacao = p_chave);
      End If;
   Elsif p_operacao = 'V' Then -- Encaminhamento
      -- Ativa registro
      null;
   Elsif p_operacao = 'C' Then -- Conclusão
      -- Atualiza a tabela de solicitações com os dados da conclusão
      Update siw.siw_solicitacao set
          conclusao        = p_data_conclusao,
          ultima_alteracao = sysdate,
          sq_siw_tramite   = (select sq_siw_tramite from siw.siw_tramite where sq_menu = p_menu and sigla='AT')
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de projetos com os dados da conclusão
      Update siw.pj_projeto set
          fim_real        = p_fim_real,
          concluida       = p_concluida,
          data_conclusao  = p_data_conclusao,
          nota_conclusao  = trim(p_nota_conclusao),
          custo_real      = p_custo_real
      where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'O' Then -- Opinião
      -- Atualiza a tabela de solicitações com a opinião do solicitante
      Update siw.siw_solicitacao set
          opiniao         = p_opiniao
      where sq_siw_solicitacao = p_chave;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutAcaoGeral_IS;
/
