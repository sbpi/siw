create or replace procedure SP_PutSolicConc
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_fim                 in varchar2  default null,
    p_executor            in number    default null,
    p_nota_conclusao      in varchar2  default null,
    p_valor               in number    default null,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2  default null,
    p_financeiro_menu     in number    default null,
    p_financeiro_tramite  in number    default null,
    p_financeiro_resp     in number    default null,
    p_situacao            in number    default null,
    p_enquadramento       in number    default null,
    p_fundo_fixo          in varchar2  default null
   ) is
   i                number(18);
   w_chave_dem      number(18) := null;
   w_chave_arq      number(18) := null;
   w_cliente        siw_menu.sq_pessoa%type;
   w_menu           siw_menu%rowtype;
   w_menu_lic       siw_menu%rowtype;
   w_sg_modulo      siw_modulo.sigla%type;
   w_sg_menu        siw_menu.sigla%type;
   w_solic          siw_solicitacao%rowtype;
   w_pedido         cl_solicitacao%rowtype;
   w_chave_nova     siw_solicitacao.sq_siw_solicitacao%type;
   w_valor          siw_solicitacao.valor%type;
   w_rubrica        number(18);
   w_lancamento     number(18);
   w_sq_financ      number(18)   := null;
   w_cd_financ      varchar2(60) := null;
   w_sq_doc         number(18)   := null;
   w_operacao       varchar2(1);
   w_mod_pa         varchar2(1);
   w_codigo_interno varchar2(60);
   w_nu_guia        pa_documento_log.nu_guia%type;
   w_ano_guia       pa_documento_log.ano_guia%type;
   w_unidade_guia   number(18);
   
   cursor c_vencedor is
       select x.*
         from (select b.sq_solicitacao_item, b.sq_material, c.fornecedor, sum(c.valor_item) as valor
                 from siw_solicitacao                  a
                      inner   join cl_solicitacao_item b  on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                        left  join cl_item_fornecedor  c  on (b.sq_solicitacao_item = c.sq_solicitacao_item and
                                                              'N'                   = c.pesquisa)
                      inner   join cl_solicitacao      d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                where a.sq_siw_solicitacao = p_chave
               group by b.sq_solicitacao_item, b.sq_material, c.fornecedor
               order by 1,2,3
               ) x,
               (select b.sq_solicitacao_item, b.sq_material, min(c.valor_item) as valor
                 from siw_solicitacao                  a
                      inner   join cl_solicitacao_item b  on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                        left  join cl_item_fornecedor  c  on (b.sq_solicitacao_item = c.sq_solicitacao_item and
                                                              'N'                   = c.pesquisa)
                      inner   join cl_solicitacao      d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                where a.sq_siw_solicitacao = p_chave
               group by b.sq_solicitacao_item, b.sq_material
               order by 1,2,3
               ) y
         where x.sq_solicitacao_item = y.sq_solicitacao_item
           and x.sq_material         = y.sq_material
           and x.valor               = y.valor;

   cursor c_protocolos is
       select a.protocolo, a.eliminacao, d.sq_siw_tramite
         from pa_eliminacao                      a
              inner     join siw_solicitacao     b  on (a.protocolo   = b.sq_siw_solicitacao)
                inner   join siw_menu            c  on (b.sq_menu     = c.sq_menu)
                  inner join siw_tramite         d  on (c.sq_menu     = d.sq_menu and
                                                        d.sigla       = 'EL'
                                                       )
        where a.sq_siw_solicitacao = p_chave;

   cursor c_itens is
       select b.sq_solicitacao_item, b.sq_material, 
              coalesce(max(c.valor_unidade),0) as maximo, 
              coalesce(min(c.valor_unidade),0) as minimo, 
              coalesce(avg(c.valor_unidade),0) as medio
         from siw_solicitacao                  a
              inner   join cl_solicitacao_item b  on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                left  join cl_item_fornecedor  c  on (b.sq_solicitacao_item = c.sq_solicitacao_item and
                                                      'N'                   = c.pesquisa)
              inner   join cl_solicitacao      d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
        where a.sq_siw_solicitacao = p_chave
       group by b.sq_solicitacao_item, b.sq_material
       order by 1,2,3;

   cursor c_financeiro_geral is
      select distinct x.codigo_interno as cd_interno, w.sq_pessoa as cliente, w.sq_menu, y.sq_unidade, 
             x.justificativa as descricao, 
             x1.processo,
             soma_dias(w.sq_pessoa,trunc(sysdate),x1.dias_validade_proposta,'C') as vencimento, 
             w1.sq_cidade_padrao as sq_cidade, x.sq_siw_solicitacao as sq_solic_pai, 
             w3.sq_forma_pagamento,
             w4.sq_tipo_documento,
             'Registro gerado automaticamente pelo sistema de licitações' as observacao, z.sq_lancamento, 
             trunc(x.inicio) as inicio, x.fim, 
             x3.fornecedor, x3.valor, x3.sq_tipo_pessoa
        from siw_menu                           w
             inner   join siw_cliente           w1 on (w.sq_pessoa           = w1.sq_pessoa)
               inner join siw_tramite           w2 on (w.sq_menu             = w2.sq_menu and
                                                       w2.sq_siw_tramite     = p_financeiro_tramite
                                                      )
               inner join co_forma_pagamento    w3 on (w1.sq_pessoa          = w3.cliente and w3.sigla = 'CREDITO')
               inner join fn_tipo_documento     w4 on (w1.sq_pessoa          = w4.cliente and w4.sigla = 'NF'),
             siw_solicitacao                    x
             inner     join siw_tramite         x5 on (x.sq_siw_tramite      = x5.sq_siw_tramite)
             inner     join cl_solicitacao      x1 on (x.sq_siw_solicitacao  = x1.sq_siw_solicitacao)
             inner     join (select l.sq_siw_solicitacao, k.fornecedor, m.sq_tipo_pessoa, sum(k.valor_item) as valor
                               from cl_item_fornecedor             k
                                    inner join cl_solicitacao_item l on (k.sq_solicitacao_item = l.sq_solicitacao_item)
                                    inner join co_pessoa           m on (k.fornecedor          = m.sq_pessoa)
                              where k.pesquisa = 'N'
                                and k.vencedor = 'S'
                             group by l.sq_siw_solicitacao, k.fornecedor, m.sq_tipo_pessoa
                            )                   x3 on (x.sq_siw_solicitacao  = x3.sq_siw_solicitacao)
             left      join (select a.sq_siw_solicitacao as sq_financeiro, a.sq_solic_pai, a.descricao, c.sq_tipo_lancamento
                               from siw_solicitacao                a
                                    inner   join siw_tramite       b on (a.sq_siw_tramite     = b.sq_siw_tramite and b.sigla <> 'CA')
                                    inner   join fn_lancamento     c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
                            )                   x2 on (x.sq_siw_solicitacao  = x2.sq_solic_pai),
             sg_autenticacao                    y,
             (select c2.sq_tipo_lancamento as sq_lancamento, c2.nome as nm_lancamento
                from siw_solicitacao                      a
                     inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite)
                     inner     join cl_solicitacao        a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join cl_vinculo_financeiro c  on (a1.sq_financeiro             = c.sq_clvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
               where a.sq_siw_solicitacao = p_chave
             )                                  z
       where w.sq_menu            = p_financeiro_menu
         and x.sq_siw_solicitacao = p_chave
         and y.sq_pessoa          = p_financeiro_resp
         and x2.sq_financeiro     is null;

   cursor c_financeiro_item (l_fornecedor in number) is
      select a3.sq_solicitacao_item, a3.quantidade, a4.valor_unidade, a5.nome as nm_material, a5.descricao as ds_material,
             c1.sq_projeto_rubrica as sq_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica
        from siw_solicitacao                      a
             inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite)
             inner     join cl_solicitacao        a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
               inner   join cl_vinculo_financeiro c  on (a1.sq_financeiro             = c.sq_clvinculo_financeiro)
                 inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
               inner   join cl_solicitacao_item   a3 on (a1.sq_siw_solicitacao        = a3.sq_siw_solicitacao)
                 inner join cl_item_fornecedor    a4 on (a3.sq_solicitacao_item       = a4.sq_solicitacao_item and
                                                         a4.pesquisa                  = 'N' and
                                                         a4.vencedor                  = 'S' and
                                                         a4.fornecedor                = l_fornecedor
                                                        )
                 inner join cl_material           a5 on (a3.sq_material               = a5.sq_material)
       where a.sq_siw_solicitacao = p_chave;

   cursor c_protocolo is
     select a.sq_especie_documento, a.sigla, a.sq_assunto, b.sq_menu, b.sq_unid_executora, 
            c.protocolo_siw, c.codigo_interno, trunc(c.inclusao) as inclusao, 
            p_nota_conclusao as descricao, 'N' as processo, 'N' as circular,
            c.solicitante as sq_pessoa, c.sq_unidade,
            f.sq_siw_tramite,
            g.despacho_autuar as novo_tramite, h.sigla as sg_despacho,
            'autuação de processo' as nm_novo_tramite
       from pa_especie_documento          a
            inner   join siw_menu         b on (a.cliente              = b.sq_pessoa and
                                                b.sigla                = 'PADCAD'
                                               )
              inner join siw_tramite      f on (b.sq_menu              = f.sq_menu and
                                                f.ordem                = 1
                                               )
            inner   join pa_parametro     g on (a.cliente              = g.cliente)
              inner join pa_tipo_despacho h on (g.despacho_autuar      = h.sq_tipo_despacho),
            siw_solicitacao               c
            inner   join siw_menu         d on (c.sq_menu              = d.sq_menu and
                                                d.sigla                = 'CLPCCAD'
                                               )
            inner   join cl_solicitacao   e on (c.sq_siw_solicitacao   = e.sq_siw_solicitacao)
      where a.cliente            = w_cliente
        and a.sigla              = 'SOCS'
        and c.sq_siw_solicitacao = p_chave;

begin
   -- Recupera o módulo da solicitação
   select b.sigla, c.sigla into w_sg_menu, w_sg_modulo
     from siw_solicitacao         a
          inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
            inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
    where a.sq_siw_solicitacao = p_chave;
    
   -- Recupera os dados do serviço
   select * into w_menu from siw_menu where sq_menu = p_menu;
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
       p_tramite,                 sysdate,            'N',
       case w_sg_menu when 'EVCAD' then 'Envio do evento' else 'Conclusão da solicitação' end);
       
   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      conclusao      = coalesce(to_date(p_fim,'dd/mm/yyyy, hh24:mi'),sysdate),
      executor       = p_executor,
      recebedor      = p_financeiro_resp,
      valor          = coalesce(p_valor,valor),
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu = p_menu 
                           and Nvl(sigla,'z')='AT'
                       )
   Where sq_siw_solicitacao = p_chave;

   if w_sg_modulo <> 'PA' and w_sg_modulo <> 'CO' then 
      update siw_solicitacao set observacao     = p_nota_conclusao where sq_siw_solicitacao = p_chave;
   end if;
   
   if w_sg_modulo = 'CO' then 
      update cl_solicitacao
         set sq_lcsituacao        = p_situacao,
             sq_modalidade_artigo = p_enquadramento,
             fundo_fixo           = coalesce(p_fundo_fixo,'N'),
             nota_conclusao       = p_nota_conclusao,
             sq_financeiro        = case coalesce(p_fundo_fixo,'N') when 'S' then null else sq_financeiro end
      where sq_siw_solicitacao = p_chave;

      -- Atualiza o código da solicitação com o número do certame
      update siw_solicitacao a
         set a.codigo_interno = coalesce((select numero_certame from cl_solicitacao b where b.sq_siw_solicitacao = a.sq_siw_solicitacao),a.codigo_interno)
      where a.sq_siw_solicitacao = p_chave;
   end if;

   for crec in c_vencedor loop
      -- Grava os itens de uma licitação, indicando o vencedor
       update cl_item_fornecedor 
          set vencedor = 'S' 
       where sq_solicitacao_item = crec.sq_solicitacao_item
         and sq_material         = crec.sq_material
         and fornecedor          = crec.fornecedor;
       
       -- Grava as quantidades compradas
       update cl_solicitacao_item
          set quantidade_autorizada = quantidade
       where sq_solicitacao_item = crec.sq_solicitacao_item;
   end loop;
   
   -- Grava os itens de uma licitação, indicando os preços mínimo, médio e máximo
   for crec in c_itens loop
       update cl_solicitacao_item a set
          a.preco_menor = crec.minimo,
          a.preco_maior = crec.maximo,
          a.preco_medio = crec.medio
       where sq_solicitacao_item = crec.sq_solicitacao_item;
   end loop;
   
   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, sysdate, 
              p_tamanho,   p_tipo,        p_caminho, p_nome_original
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Insere registro em SIW_SOLIC_LOG_ARQ
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
      values (w_chave_dem, w_chave_arq);
   End If;

   If w_mod_pa = 'S' and coalesce(p_fundo_fixo,'S') = 'N' Then
      for crec in c_protocolo loop
          -- Cria o documento no sistema de protocolo
          sp_putdocumentogeral(p_operacao           => case when crec.protocolo_siw is null then 'I' else 'A' end,
                               p_chave              => crec.protocolo_siw,
                               p_copia              => null,
                               p_menu               => crec.sq_menu,
                               p_unidade            => crec.sq_unidade,
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
                               p_data_recebimento   => trunc(sysdate),
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
                               p_unidade_origem     => crec.sq_unidade,
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
          Elsif crec.sq_unidade <> crec.sq_unid_executora Then
             -- Recebe automaticamente
             sp_putdocumentoreceb(p_operacao 	 => 'R',
                                  p_pessoa     => p_pessoa,
                                  p_unid_autua => crec.sq_unid_executora,
                                  p_nu_guia    => w_nu_guia,
                                  p_ano_guia   => w_ano_guia,
                                  p_observacao => null);
             
          End If;
          -- Vincula a viagem com o protocolo
          update siw_solicitacao set protocolo_siw = w_chave_nova where sq_siw_solicitacao = p_chave;
      end loop;
   End If;

   If w_menu.sigla = 'MTCONSUMO' Then
      -- Atualiza o valor da solicitação
      update siw_solicitacao a
         set valor = (select sum(x.valor_unitario)
                        from mt_saida                 w
                             inner join mt_saida_item x on (w.sq_mtsaida = x.sq_mtsaida)
                       where w.sq_siw_solicitacao = a.sq_siw_solicitacao
                         and x.quantidade_entregue > 0
                     )
      where a.sq_siw_solicitacao = p_chave;
      
      -- Atualiza o saldo de estoque
      update mt_estoque_item a
         set saldo_atual = saldo_atual - (select sum(y.quantidade)
                                            from mt_saida                      w
                                                 inner   join mt_saida_item    x on (w.sq_mtsaida    = x.sq_mtsaida)
                                                   inner join mt_saida_estoque y on (x.sq_saida_item = y.sq_saida_item)
                                          where w.sq_siw_solicitacao = p_chave
                                            and y.sq_estoque_item    = a.sq_estoque_item
                                         )
      where sq_estoque_item in (select sq_estoque_item
                                  from mt_saida                      w
                                       inner   join mt_saida_item    x on (w.sq_mtsaida    = x.sq_mtsaida)
                                         inner join mt_saida_estoque y on (x.sq_saida_item = y.sq_saida_item)
                                where w.sq_siw_solicitacao = p_chave
                                  and y.quantidade         > 0
                               );
   Elsif w_menu.sigla = 'PAELIM' Then
      -- Se eliminação de protocolo, coloca os protocolos da solicitação no tramite correto
      for crec in c_protocolos loop
         update siw_solicitacao a
            set a.conclusao    = crec.eliminacao,
                sq_siw_tramite = crec.sq_siw_tramite
         where sq_siw_solicitacao = crec.protocolo or a.sq_solic_pai = crec.protocolo;
      end loop;
   Elsif p_financeiro_resp is not null Then
      -- Cria/atualiza lançamento financeiro
      for crec in c_financeiro_geral loop
          sp_putfinanceirogeral(
                             p_operacao           => 'I',
                             p_cliente            => crec.cliente,
                             p_chave              => null,
                             p_menu               => crec.sq_menu,
                             p_sq_unidade         => crec.sq_unidade,
                             p_solicitante        => p_financeiro_resp,
                             p_cadastrador        => p_financeiro_resp,
                             p_descricao          => crec.descricao,
                             p_vencimento         => crec.vencimento,
                             p_valor              => crec.valor,
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
                             p_numero_processo    => crec.processo,
                             p_per_ini            => crec.inicio,
                             p_per_fim            => crec.fim,
                             p_chave_nova         => w_sq_financ,
                             p_codigo_interno     => w_cd_financ
                            );

          -- Atualiza os dados do beneficiário
          update fn_lancamento set pessoa = crec.fornecedor where sq_siw_solicitacao = w_sq_financ;

          -- Cria os documentos
          sp_putlancamentodoc(
                             p_operacao           => 'I',
                             p_chave              => w_sq_financ,
                             p_chave_aux          => null,
                             p_sq_tipo_documento  => crec.sq_tipo_documento,
                             p_numero             => 'AJUSTAR',
                             p_data               => trunc(sysdate),
                             p_serie              => null,
                             p_valor              => crec.valor,
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
          i := 0;
          For drec in c_financeiro_item (crec.fornecedor) Loop
               i := i + 1;
               sp_putlancamentoitem(
                             p_operacao           => 'I',
                             p_chave              => w_sq_doc,
                             p_chave_aux          => null,
                             p_sq_projeto_rubrica => drec.sq_rubrica,
                             p_solic_item         => drec.sq_solicitacao_item,
                             p_descricao          => drec.nm_material,
                             p_quantidade         => drec.quantidade,
                             p_valor_unitario     => drec.valor_unidade,
                             p_ordem              => i
                            );
          End Loop;
      End Loop;
   End If;

end SP_PutSolicConc;
/
