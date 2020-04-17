create or replace procedure SP_PutDemandaConc
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_inicio_real         in date      default null,
    p_fim_real            in date      default null,
    p_nota_conclusao      in varchar2  default null,
    p_custo_real          in number    default null,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2  default null
   ) is
   w_chave_dem      number(18) := null;
   w_chave_arq      number(18) := null;
   w_solic          siw_solicitacao%rowtype;
   w_menu           siw_menu%rowtype;
   w_modulo         siw_modulo%rowtype;
   w_data_atual     date := sysdate;
   w_cliente        number(18);
   w_mod_pa         varchar2(1);
   w_chave_nova     number(18);
   w_codigo_interno varchar2(60);
   w_nu_guia        pa_documento_log.nu_guia%type;
   w_ano_guia       pa_documento_log.ano_guia%type;
   w_unidade_guia   number(18);
   
   w_ci             number(18);
   w_ee             number(18);
   w_pp             number(18);
   w_reembolso      pd_missao.reembolso%type;
   w_ressarcimento  pd_missao.ressarcimento%type;
   w_beneficiario   number(18);
   w_sq_financ      number(18)   := null;
   w_cd_financ      varchar2(60) := null;
   w_sq_doc         number(18)   := null;
   w_existe         number(18);
   w_conta          co_pessoa_conta%rowtype;
   i                number(18);
   
   cursor c_protocolo is
     select a.sq_especie_documento, a.sigla, a.sq_assunto, b.sq_menu,
            c.codigo_interno, trunc(c.inclusao) as inclusao, c.descricao,
            d.sq_unid_executora, 'N' as processo, 'N' as circular,
            e.sq_pessoa,
            f.sq_siw_tramite,
            g.despacho_arqsetorial as novo_tramite, h.sigla as sg_despacho,
            'arquivamento setorial' as nm_novo_tramite
       from pa_especie_documento          a
            inner   join siw_menu         b on (a.cliente              = b.sq_pessoa and
                                                b.sigla                = 'PADCAD'
                                               )
              inner join siw_tramite      f on (b.sq_menu              = f.sq_menu and
                                                f.ordem                = 1
                                               )
            inner   join pa_parametro     g on (a.cliente              = g.cliente)
              inner join pa_tipo_despacho h on (g.despacho_arqsetorial = h.sq_tipo_despacho),
            siw_solicitacao               c
            inner   join siw_menu         d on (c.sq_menu              = d.sq_menu)
            inner   join pd_missao        e on (c.sq_siw_solicitacao   = e.sq_siw_solicitacao)
      where a.cliente            = w_cliente
        and a.sigla              = 'SOVI'
        and c.sq_siw_solicitacao = p_chave;
   
   cursor c_missao is
      select * from pd_missao where sq_siw_solicitacao = p_chave;

   cursor c_ressarcimento_geral is
      select x.codigo_interno as cd_interno, x1.sq_moeda_ressarcimento, w.sq_pessoa as cliente, w.sq_menu, w.sq_unid_executora, 
             'Devolução de valores da '||x.codigo_interno||'.' as descricao,
             soma_dias(w_cliente,trunc(sysdate),2,'U') as vencimento, 
             w1.sq_cidade_padrao as sq_cidade, x.sq_siw_solicitacao as sq_solic_pai, 
             'Registro gerado automaticamente pelo sistema de viagens' as observacao,
             coalesce(x1.sq_forma_pagamento, w2.sq_forma_pagamento) as sq_forma_pagamento, x.inicio, x.fim, y.sq_tipo_documento,
             x2.sq_financeiro, x2.sq_lancamento_doc  as sq_documento, z.sq_tipo_lancamento, z.nm_lancamento,
             x2.cc_debito, x2.cc_credito,
             x3.sq_tipo_pessoa
        from siw_menu                          w
             inner     join siw_cliente        w1 on (w.sq_pessoa           = w1.sq_pessoa)
               inner   join co_forma_pagamento w2 on (w1.sq_pessoa          = w2.cliente and w2.sigla = 'CREDITO'),
             siw_solicitacao                   x
             inner     join siw_tramite       x5 on (x.sq_siw_tramite       = x5.sq_siw_tramite)
             inner     join pd_missao         x1 on (x.sq_siw_solicitacao   = x1.sq_siw_solicitacao and
                                                     x1.ressarcimento       = 'S' and
                                                     x1.sq_pdvinculo_ressarcimento is not null
                                                    )
               inner   join co_pessoa         x3 on (x1.sq_pessoa          = x3.sq_pessoa)
             left      join (select a.sq_siw_solicitacao as sq_financeiro, a.sq_solic_pai, a.descricao, c.sq_tipo_lancamento, 
                                    c.cc_debito, c.cc_credito,
                                    d.sq_lancamento_doc, e.nome as nm_lancamento
                               from siw_solicitacao                 a
                                    inner   join siw_tramite        b on (a.sq_siw_tramite     = b.sq_siw_tramite and b.sigla <> 'CA')
                                    inner   join fn_lancamento      c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
                                      inner join fn_lancamento_doc  d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
                                      inner join fn_tipo_lancamento e on (c.sq_tipo_lancamento = e.sq_tipo_lancamento)
                            )                 x2 on (x.sq_siw_solicitacao   = x2.sq_solic_pai and 
                                                     instr(lower(x2.descricao),'devolução')>0
                                                    ),
             fn_tipo_documento             y,
             (select sq_tipo_lancamento, nome as nm_lancamento
                from (select sq_tipo_lancamento, nome
                        from fn_tipo_lancamento k
                       where k.receita                = 'S'
                         and k.sq_tipo_lancamento_pai is null
                         and k.ativo                  = 'S'
                      order by k.nome
                     ) k1
               where rownum = 1
             )                             z
       where w.sq_pessoa          = w_cliente
         and w.sigla              = 'FNREVENT'
         and x.sq_siw_solicitacao = p_chave
         and y.sigla              = 'VG';


   cursor c_ressarcimento_item is
      select sq_projeto_rubrica as sq_rubrica, cd_rubrica, nm_rubrica, sq_moeda, sg_moeda, nm_moeda, sb_moeda, sum(valor) as valor,
             case tp_despesa 
                  when 'DEV' then 'Devolução de valores'
                  else 'Não identificado' 
             end as nm_despesa
        from (select 'DEV' as tp_despesa, null as sq_diaria, a1.ressarcimento_valor as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     b.sq_moeda, b.sigla as sg_moeda, b.nome as nm_moeda, b.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao          = a1.sq_siw_solicitacao and
                                                                 a1.ressarcimento              = 'S'
                                                                )
                       left    join co_moeda              b  on (a1.sq_moeda_ressarcimento     = b.sq_moeda)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_ressarcimento = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica          = c1.sq_projeto_rubrica)
               where a.sq_siw_solicitacao = p_chave
              ) k
      group by sq_projeto_rubrica, cd_rubrica, nm_rubrica, sq_moeda, sg_moeda, nm_moeda, sb_moeda, tp_despesa;

   cursor c_reembolso is
      select x.codigo_interno as cd_interno, w.sq_pessoa as cliente, w.sq_menu, w.sq_unid_executora, 
             'Reembolso da '||x.codigo_interno||' ('||x4.nm_moeda||') '||fValor(x4.valor,'T') as descricao,
             soma_dias(w_cliente,trunc(sysdate),2,'U') as vencimento, 
             w1.sq_cidade_padrao as sq_cidade, x.sq_siw_solicitacao as sq_solic_pai, 
             'Registro gerado automaticamente pelo sistema de viagens' as observacao, z.sq_lancamento, 
             coalesce(x1.sq_forma_pagamento, w2.sq_forma_pagamento) as sq_forma_pagamento, x.inicio, x.fim, y.sq_tipo_documento,
             x2.sq_financeiro, x2.sq_lancamento_doc  as sq_documento, coalesce(x2.sg_tramite,'-') as sg_tramite,
             x2.cc_debito, x2.cc_credito,
             x3.sq_tipo_pessoa,
             x4.sq_rubrica, x4.cd_rubrica, x4.nm_rubrica, x4.sq_moeda, x4.sg_moeda, x4.nm_moeda, x4.sb_moeda, x4.valor
        from siw_menu                          w
             inner     join siw_cliente        w1 on (w.sq_pessoa           = w1.sq_pessoa)
               inner   join co_forma_pagamento w2 on (w1.sq_pessoa          = w2.cliente and w2.sigla = 'CREDITO'),
             siw_solicitacao                   x
             inner     join siw_tramite       x5 on (x.sq_siw_tramite      = x5.sq_siw_tramite)
             inner     join pd_missao         x1 on (x.sq_siw_solicitacao  = x1.sq_siw_solicitacao and
                                                     x1.reembolso          = 'S'
                                                    )
               inner   join co_pessoa         x3 on (x1.sq_pessoa          = x3.sq_pessoa)
               inner   join (select sq_siw_solicitacao, 
                                    sq_projeto_rubrica as sq_rubrica, cd_rubrica, nm_rubrica, sq_moeda, sg_moeda, nm_moeda, sb_moeda, sum(valor) as valor
                               from (select a.sq_siw_solicitacao, 'RMB' as tp_despesa, null as sq_diaria, b.valor_autorizado as valor,
                                            c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                                            b1.sq_moeda, b1.sigla as sg_moeda, b1.nome as nm_moeda, b1.simbolo as sb_moeda
                                       from siw_solicitacao                      a
                                            inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite)
                                            inner     join pd_reembolso          b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                                              inner   join co_moeda              b1 on (b.sq_moeda                   = b1.sq_moeda)
                                            inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                                              inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                                                inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                                      where a.sq_siw_solicitacao = p_chave
                                    ) k
                             group by sq_siw_solicitacao, sq_projeto_rubrica, cd_rubrica, nm_rubrica, sq_moeda, sg_moeda, nm_moeda, sb_moeda
                            )                 x4 on (x.sq_siw_solicitacao  = x4.sq_siw_solicitacao)
             left      join (select a.sq_siw_solicitacao as sq_financeiro, a.sq_solic_pai, a.descricao, c.sq_tipo_lancamento, d.sq_lancamento_doc,
                                    b.sigla as sg_tramite, c.cc_debito, c.cc_credito
                               from siw_solicitacao                a
                                    inner   join siw_tramite       b on (a.sq_siw_tramite     = b.sq_siw_tramite and b.sigla <> 'CA')
                                    inner   join fn_lancamento     c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
                                      inner join fn_lancamento_doc d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
                            )                 x2 on (x.sq_siw_solicitacao  = x2.sq_solic_pai and
                                                     0                     < instr(x2.descricao,'('||x4.nm_moeda||')') and 
                                                     0                     = instr(lower(x2.descricao),'adiantamento')
                                                    ),
             fn_tipo_documento             y,
             (select sq_tipo_lancamento as sq_lancamento, nm_lancamento
                from (select c2.sq_tipo_lancamento, c2.nome as nm_lancamento
                        from siw_solicitacao                      a
                             inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite)
                             inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                               inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                                 inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       where a.sq_siw_solicitacao = p_chave
                     ) k
             )                             z
       where w.sigla              = 'FNDVIA'
         and x.sq_siw_solicitacao = p_chave
         and y.sigla              = 'VG'
         and z.sq_lancamento      = case when x2.sq_financeiro is null then z.sq_lancamento else x2.sq_tipo_lancamento end
         and x4.valor             > 0;

begin
  -- Recupera o cliente e os dados da solicitação, do menu e do módulo
  select * into w_solic  from siw_solicitacao where sq_siw_solicitacao = p_chave;
  select * into w_menu   from siw_menu        where sq_menu            = w_solic.sq_menu;
  select * into w_modulo from siw_modulo      where sq_modulo          = w_menu.sq_modulo;
  w_cliente := w_menu.sq_pessoa;
  
  -- Verifica se o cliente tem o módulo de protocolo e arquivo contratado
  select case count(*) when 0 then 'N' else 'S' end
    into w_mod_pa
    from siw_cliente_modulo a
         inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
   where a.sq_pessoa = w_cliente 
     and b.sigla     = 'PA';
  
   -- Recupera a chave do log
   select sq_siw_solic_log.nextval into w_chave_dem from dual;
   
   -- Insere registro na tabela de log da solicitacao
   Insert Into siw_solic_log 
      (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
       sq_siw_tramite,            data,               devolucao, 
       observacao
      )
   Values
      (w_chave_dem,               p_chave,            p_pessoa,
       p_tramite,                 w_data_atual,       'N',
       'Conclusão');
       
   -- Atualiza o registro da demanda com os dados da conclusão.
   Update gd_demanda set
      inicio_real     = coalesce(p_inicio_real,inicio_real),
      fim_real        = coalesce(p_fim_real,fim_real),
--      nota_conclusao  = coalesce(p_nota_conclusao,nota_conclusao),
      custo_real      = coalesce(p_custo_real,custo_real),
      concluida       = 'S',
      data_conclusao  = w_data_atual
   Where sq_siw_solicitacao = p_chave;

   -- Atualiza a situação da solicitação
   Update siw_solicitacao
      set sq_siw_tramite =
          (select sq_siw_tramite
             from siw_tramite
            where sq_menu = p_menu
              and Nvl(sigla, 'z') = 'AT')
      Where sq_siw_solicitacao = p_chave;

   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, w_data_atual, 
              p_tamanho,   p_tipo,        p_caminho, p_nome_original
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Insere registro em SIW_SOLIC_LOG_ARQ
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
      values (w_chave_dem, w_chave_arq);
   End If;
   
   If w_cliente = 10135 and w_mod_pa = 'S' Then
      for crec in c_protocolo loop
          -- Cria o documento no sistema de protocolo
          sp_putdocumentogeral(p_operacao           => 'I',
                               p_chave              => null,
                               p_copia              => null,
                               p_menu               => crec.sq_menu,
                               p_unidade            => crec.sq_unid_executora,
                               p_unid_autua         => crec.sq_unid_executora,
                               p_solicitante        => p_pessoa,
                               p_cadastrador        => p_pessoa,
                               p_solic_pai          => null,
                               p_vinculo            => null,
                               p_processo           => 'N',
                               p_circular           => 'N',
                               p_especie_documento  => crec.sq_especie_documento,
                               p_doc_original       => crec.codigo_interno,
                               p_inicio             => crec.inclusao,
                               p_volumes            => null,
                               p_dt_autuacao        => null,
                               p_copias             => null,
                               p_natureza_documento => null,
                               p_fim                => null,
                               p_data_recebimento   => crec.inclusao,
                               p_interno            => 'S',
                               p_pessoa_origem      => null,
                               p_pessoa_interes     => crec.sq_pessoa,
                               p_cidade             => null,
                               p_assunto            => crec.sq_assunto,
                               p_descricao          => crec.descricao,
                               p_chave_nova         => w_chave_nova,
                               p_codigo_interno     => w_codigo_interno
                              );
          -- Envia para arquivamento setorial
          sp_putdocumentoenvio(p_menu               => crec.sq_menu,
                               p_chave              => w_chave_nova,
                               p_pessoa             => p_pessoa,
                               p_tramite            => crec.sq_siw_tramite,
                               p_interno            => 'S',
                               p_unidade_origem     => crec.sq_unid_executora,
                               p_unidade_destino    => crec.sq_unid_executora,
                               p_pessoa_destino     => null,
                               p_tipo_despacho      => crec.novo_tramite,
                               p_prefixo            => null,
                               p_numero             => null,
                               p_ano                => null,
                               p_despacho           => 'Envio automatizado para '||crec.nm_novo_tramite||'.',
                               p_emite_aviso        => 'N',
                               p_dias_aviso         => null,
                               p_retorno_limite     => null,
                               p_pessoa_externa     => null,
                               p_unidade_externa    => null,
                               p_nu_guia            => w_nu_guia,
                               p_ano_guia           => w_ano_guia,
                               p_unidade_autuacao   => w_unidade_guia
                              );
          If crec.sg_despacho = 'ARQUIVAR S' Then
             -- Executa arquivamento setorial
             sp_putdocumentoarqset(p_chave             => w_chave_nova,
                                   p_usuario           => p_pessoa,
                                   p_observacao        => p_nota_conclusao
                                  );
          End If;
          -- Vincula a viagem com o protocolo
          update siw_solicitacao set protocolo_siw = w_chave_nova where sq_siw_solicitacao = p_chave;
      end loop;
   End If;
   
   If w_cliente = 17305 Then
      -- Recupera os dados da solicitação. Se for contratado, a unidade solicitante aprova trâmites de chefia imediata. Caso contrário, será a unidade proponente.
      select a.reembolso, a.ressarcimento, a.sq_pessoa
        into w_reembolso, w_ressarcimento, w_beneficiario
        from pd_missao                             a
             left    join sg_autenticacao         a1 on (a.sq_pessoa          = a1.sq_pessoa)
             left    join gp_contrato_colaborador a2 on (a.sq_pessoa          = a2.sq_pessoa and a2.fim is null)
             inner   join co_pessoa                b on (a.sq_pessoa          = b.sq_pessoa)
               inner join co_tipo_vinculo          c on (b.sq_tipo_vinculo    = c.sq_tipo_vinculo)
             inner   join siw_solicitacao          d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
             inner   join gd_demanda               e on (a.sq_siw_solicitacao = e.sq_siw_solicitacao)
       where a.sq_siw_solicitacao = p_chave;
    
      If w_reembolso = 'S' Then
          -- Cria/atualiza lançamento financeiro para o reembolso
         for crec in c_reembolso loop
           w_cd_financ := null;
           If crec.sg_tramite <> 'AT' Then
             sp_putfinanceirogeral(
                               p_operacao           => case when crec.sq_financeiro is null then 'I' else 'A' end,
                               p_cliente            => crec.cliente,
                               p_chave              => crec.sq_financeiro,
                               p_menu               => crec.sq_menu,
                               p_sq_unidade         => crec.sq_unid_executora,
                               p_solicitante        => p_pessoa,
                               p_cadastrador        => p_pessoa,
                               p_descricao          => crec.descricao,
                               p_vencimento         => case when crec.vencimento > trunc(crec.inicio) then crec.vencimento else trunc(crec.inicio) end,
                               p_valor              => 0,
                               p_data_hora          => 3,
                               p_aviso              => 'S',
                               p_dias               => '2',
                               p_cidade             => crec.sq_cidade,
                               p_projeto            => crec.sq_solic_pai,
                               p_observacao         => crec.observacao,
                               p_sq_tipo_lancamento => crec.sq_lancamento,
                               p_sq_forma_pagamento => crec.sq_forma_pagamento,
                               p_sq_tipo_pessoa     => crec.sq_tipo_pessoa,
                               p_tipo_rubrica       => 5, -- despesas
                               p_per_ini            => crec.inicio,
                               p_per_fim            => crec.fim,
                               p_moeda              => crec.sq_moeda,
                               p_cc_debito          => crec.cc_debito,
                               p_cc_credito         => crec.cc_credito,
                               p_chave_nova         => w_sq_financ,
                               p_codigo_interno     => w_cd_financ
                              );
             For drec in c_missao Loop
                -- Atualiza os dados do beneficiário
                update fn_lancamento set
                   pessoa           = drec.sq_pessoa,
                   sq_agencia       = drec.sq_agencia,
                   operacao_conta   = drec.operacao_conta,
                   numero_conta     = drec.numero_conta,
                   sq_pais_estrang  = drec.sq_pais_estrang,
                   aba_code         = drec.aba_code,
                   swift_code       = drec.swift_code,
                   endereco_estrang = drec.endereco_estrang,
                   banco_estrang    = drec.banco_estrang,
                   agencia_estrang  = drec.agencia_estrang,
                   cidade_estrang   = drec.cidade_estrang,
                   informacoes      = drec.informacoes,
                   codigo_deposito  = drec.codigo_deposito
                where sq_siw_solicitacao = w_sq_financ;
             End Loop;
             sp_putlancamentodoc(
                               p_operacao           => case when crec.sq_documento is null then 'I' else 'A' end,
                               p_chave              => w_sq_financ,
                               p_chave_aux          => crec.sq_documento,
                               p_sq_tipo_documento  => crec.sq_tipo_documento,
                               p_numero             => nvl(crec.cd_interno,w_cd_financ),
                               p_data               => trunc(sysdate),
                               p_serie              => null,
                               p_moeda              => crec.sq_moeda,
                               p_valor              => 0,
                               p_patrimonio         => 'N',
                               p_retencao           => 'N',
                               p_tributo            => 'N',
                               p_nota               => null,
                               p_inicial            => 0,
                               p_excedente          => 0,
                               p_reajuste           => 0,
                               p_chave_nova         => w_sq_doc
                              );
             -- Cria itens do lançamento
             delete fn_documento_item where sq_lancamento_doc = w_sq_doc;
             sp_putlancamentoitem(
                           p_operacao           => 'I',
                           p_chave              => w_sq_doc,
                           p_chave_aux          => null,
                           p_sq_projeto_rubrica => crec.sq_rubrica,
                           p_descricao          => crec.sg_moeda||' ('||crec.nm_moeda||') '||fValor(crec.valor,'T'),
                           p_quantidade         => 1,
                           p_valor_unitario     => crec.valor,
                           p_ordem              => 1
                          );
    
             If crec.sq_financeiro is null Then
                -- Coloca a solicitação na fase de pagamento ou de pendência
                select sq_siw_tramite into w_ci from siw_tramite where sq_menu = crec.sq_menu and sigla='CI';
                select sq_siw_tramite into w_ee from siw_tramite where sq_menu = crec.sq_menu and sigla='EE';
                select sq_siw_tramite into w_pp from siw_tramite where sq_menu = crec.sq_menu and sigla='PP';
                    
                sp_putlancamentoenvio(
                                 p_menu          => crec.sq_menu,
                                 p_chave         => w_sq_financ,
                                 p_pessoa        => p_pessoa,
                                 p_tramite       => w_ci,
                                 p_novo_tramite  => w_ee,
                                 p_devolucao     => 'N',
                                 p_despacho      => 'Envio automático de reembolso de viagem.'
                                );
             End If;
           End If;
         End Loop;
      End If;

      If w_ressarcimento = 'S' Then
         -- Recupera a conta bancária utilizada para devolução de valores
         select count(*) into w_existe from co_pessoa_conta where sq_pessoa = w_cliente and devolucao_valor = 'S' and ativo = 'S';
         if w_existe = 1 then
            select * into w_conta from co_pessoa_conta where sq_pessoa = w_cliente and devolucao_valor = 'S' and ativo = 'S';
         end if; 
 
          -- Cria/atualiza lançamento financeiro para a devolução de valores
         for crec in c_ressarcimento_geral loop
           w_cd_financ := null;
             sp_putfinanceirogeral(
                               p_operacao           => case when crec.sq_financeiro is null then 'I' else 'A' end,
                               p_cliente            => crec.cliente,
                               p_chave              => crec.sq_financeiro,
                               p_menu               => crec.sq_menu,
                               p_sq_unidade         => crec.sq_unid_executora,
                               p_solicitante        => p_pessoa,
                               p_cadastrador        => p_pessoa,
                               p_descricao          => crec.descricao,
                               p_vencimento         => crec.vencimento,
                               p_valor              => 0,
                               p_data_hora          => 3,
                               p_aviso              => 'S',
                               p_dias               => '2',
                               p_cidade             => crec.sq_cidade,
                               p_projeto            => crec.sq_solic_pai,
                               p_observacao         => crec.observacao,
                               p_sq_tipo_lancamento => crec.sq_tipo_lancamento,
                               p_sq_forma_pagamento => crec.sq_forma_pagamento,
                               p_sq_tipo_pessoa     => crec.sq_tipo_pessoa,
                               p_tipo_rubrica       => 4, -- receitas
                               p_per_ini            => crec.inicio,
                               p_per_fim            => crec.fim,
                               p_moeda              => crec.sq_moeda_ressarcimento,
                               p_cc_debito          => crec.cc_debito,
                               p_cc_credito         => crec.cc_credito,
                               p_chave_nova         => w_sq_financ,
                               p_codigo_interno     => w_cd_financ
                              );
             For drec in c_missao Loop
                 -- Atualiza os dados do beneficiário e da conta bancária
                 if w_existe > 0 then
                    update fn_lancamento set
                       pessoa           = drec.sq_pessoa,
                       sq_agencia       = w_conta.sq_agencia,
                       operacao_conta   = w_conta.operacao,
                       numero_conta     = w_conta.numero,
                       sq_pais_estrang  = null,
                       aba_code         = null,
                       swift_code       = null,
                       endereco_estrang = null,
                       banco_estrang    = null,
                       agencia_estrang  = null,
                       cidade_estrang   = null,
                       informacoes      = null,
                       codigo_deposito  = drec.codigo_deposito
                     where sq_siw_solicitacao = w_sq_financ;
                 else
                    update fn_lancamento set
                       pessoa           = drec.sq_pessoa,
                       sq_agencia       = null,
                       operacao_conta   = null,
                       numero_conta     = null,
                       sq_pais_estrang  = null,
                       aba_code         = null,
                       swift_code       = null,
                       endereco_estrang = null,
                       banco_estrang    = null,
                       agencia_estrang  = null,
                       cidade_estrang   = null,
                       informacoes      = null,
                       codigo_deposito  = drec.codigo_deposito
                     where sq_siw_solicitacao = w_sq_financ;
                 end if;
             End Loop;
             sp_putlancamentodoc(
                               p_operacao           => case when crec.sq_documento is null then 'I' else 'A' end,
                               p_chave              => w_sq_financ,
                               p_chave_aux          => crec.sq_documento,
                               p_sq_tipo_documento  => crec.sq_tipo_documento,
                               p_numero             => nvl(crec.cd_interno,w_cd_financ),
                               p_data               => trunc(sysdate),
                               p_serie              => null,
                               p_moeda              => crec.sq_moeda_ressarcimento,
                               p_valor              => 0,
                               p_patrimonio         => 'N',
                               p_retencao           => 'N',
                               p_tributo            => 'N',
                               p_nota               => null,
                               p_inicial            => 0,
                               p_excedente          => 0,
                               p_reajuste           => 0,
                               p_chave_nova         => w_sq_doc
                              );
             -- Cria itens do lançamento
             delete fn_documento_item where sq_lancamento_doc = w_sq_doc;
             i := 0;
             For drec in c_ressarcimento_item Loop
                 i := i + 1;
                 sp_putlancamentoitem(
                               p_operacao           => 'I',
                               p_chave              => w_sq_doc,
                               p_chave_aux          => null,
                               p_sq_projeto_rubrica => drec.sq_rubrica,
                               p_descricao          => drec.nm_despesa||': '||drec.sg_moeda||' ('||drec.nm_moeda||') '||fValor(drec.valor,'T'),
                               p_quantidade         => 1,
                               p_valor_unitario     => drec.valor,
                               p_ordem              => i
                              );
             End Loop;
    
             If crec.sq_financeiro is null Then
                -- Coloca a solicitação na fase de liquidação
                select sq_siw_tramite into w_ci from siw_tramite where sq_menu = crec.sq_menu and sigla='CI';
                select sq_siw_tramite into w_ee from siw_tramite where sq_menu = crec.sq_menu and sigla='EE';
                sp_putlancamentoenvio(
                                 p_menu          => crec.sq_menu,
                                 p_chave         => w_sq_financ,
                                 p_pessoa        => p_pessoa,
                                 p_tramite       => w_ci,
                                 p_novo_tramite  => w_ee,
                                 p_devolucao     => 'N',
                                 p_despacho      => 'Envio automático de devolução de valores.' 
                                );
             End If;
         End Loop;
      End If;
   End If;

end SP_PutDemandaConc;
/
