create or replace procedure SP_PutViagemEnvio
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_novo_tramite        in number    default null,    
    p_devolucao           in varchar2,
    p_despacho            in varchar2,
    p_justificativa       in varchar2,
    p_justif_dia_util     in varchar2
   ) is
   w_cliente       number(18);
   w_chave         number(18) := null;
   w_chave_dem     number(18) := null;
   w_tramite       number(18);
   w_sg_tramite    varchar2(2);
   w_existe        number(18);
   w_sq_financ     number(18)   := null;
   w_cd_financ     varchar2(60) := null;
   w_sq_doc        number(18)   := null;
   w_operacao      varchar2(1);
   i               number(18);
   w_ci            number(18);
   w_ee            number(18);
   w_salto         number(4);
   w_reembolso     pd_missao.reembolso%type;
   w_cumprimento   pd_missao.cumprimento%type;
   
   cursor c_missao is
      select * from pd_missao where sq_siw_solicitacao = p_chave;
      
   cursor c_financeiro_geral is
      select x.codigo_interno as cd_interno, w.sq_pessoa as cliente, w.sq_menu, w.sq_unid_executora, 
             case x5.sigla when 'EE' then 'Reembolso da ' else 'Adiantamento de diárias da 'end ||x.codigo_interno||'.' as descricao,
             soma_dias(w_cliente,trunc(sysdate),2,'U') as vencimento, 
             w1.sq_cidade_padrao as sq_cidade, x.sq_siw_solicitacao as sq_solic_pai, 
             'Registro gerado automaticamente pelo sistema de viagens' as observacao, z.sq_lancamento, 
             coalesce(x1.sq_forma_pagamento, w2.sq_forma_pagamento) as sq_forma_pagamento, x.inicio, x.fim, y.sq_tipo_documento,
             x2.sq_financeiro,
             x2.sq_lancamento_doc  as sq_documento
        from siw_menu                          w
             inner     join siw_cliente        w1 on (w.sq_pessoa           = w1.sq_pessoa)
               inner   join co_forma_pagamento w2 on (w1.sq_pessoa          = w2.cliente and w2.sigla = 'CREDITO'),
             siw_solicitacao                   x
             inner     join siw_tramite       x5 on (x.sq_siw_tramite      = x5.sq_siw_tramite)
             inner     join pd_missao         x1 on (x.sq_siw_solicitacao  = x1.sq_siw_solicitacao)
             left      join (select a.sq_siw_solicitacao as sq_financeiro, a.sq_solic_pai, a.descricao, c.sq_tipo_lancamento, d.sq_lancamento_doc
                               from siw_solicitacao                a
                                    inner   join siw_tramite       b on (a.sq_siw_tramite     = b.sq_siw_tramite)
                                    inner   join fn_lancamento     c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
                                      inner join fn_lancamento_doc d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
                            )                 x2 on (x.sq_siw_solicitacao  = x2.sq_solic_pai and 
                                                     (x5.sigla <> 'EE' or (x5.sigla = 'EE' and instr(lower(x2.descricao),'adiantamento')=0))
                                                    ),
             fn_tipo_documento             y,
             (select sq_tipo_lancamento as sq_lancamento, nm_lancamento
                from (select c2.sq_tipo_lancamento, c2.nome as nm_lancamento
                        from siw_solicitacao                      a
                             inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla <> 'EE')
                             inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                             inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                               inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                                 inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       where a.sq_siw_solicitacao = p_chave
                      UNION
                      select c2.sq_tipo_lancamento, c2.nome as nm_lancamento
                        from siw_solicitacao                      a
                             inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla <> 'EE')
                             inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                             inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                               inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                                 inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       where a.sq_siw_solicitacao = p_chave
                      UNION
                      select c2.sq_tipo_lancamento, c2.nome as nm_lancamento
                        from siw_solicitacao                      a
                             inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla = 'EE')
                             inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                               inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                                 inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       where a.sq_siw_solicitacao = p_chave
                     )
             )                             z
       where w.sigla              = 'FNDVIA'
         and x.sq_siw_solicitacao = p_chave
         and y.sigla              = 'VG'
         and z.sq_lancamento      = case when x2.sq_financeiro is null then z.sq_lancamento else x2.sq_tipo_lancamento end;


   cursor c_financeiro_item is
      select sq_projeto_rubrica as sq_rubrica, cd_rubrica, nm_rubrica, sg_moeda, nm_moeda, sb_moeda, sum(valor) as valor,
             case tp_despesa 
                  when 'RMB' then 'Reembolso de viagem'
                  when 'DIA' then 'Diárias' 
                  when 'HSP' then 'Hospedagem' 
                  else 'Locação de veículos' 
             end as nm_despesa
        from (select 'RMB' as tp_despesa, null as sq_diaria, b.valor_autorizado as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     b1.sigla as sg_moeda, b1.nome as nm_moeda, b1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla = 'EE')
                     inner     join pd_reembolso          b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join co_moeda              b1 on (b.sq_moeda                   = b1.sq_moeda)
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
               where a.sq_siw_solicitacao = p_chave
              UNION
              select 'DIA' as tp_despesa, b.sq_diaria, (b.quantidade*b.valor) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla <> 'EE')
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria            = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_chave
              UNION
              select 'VEI' as tp_despesa, b.sq_diaria, (-1*b.valor*b.veiculo_qtd*b.veiculo_valor/100) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla <> 'EE')
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_veiculo    = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_chave
             )
      group by sq_projeto_rubrica, cd_rubrica, nm_rubrica, sg_moeda, nm_moeda, sb_moeda, tp_despesa;
   
begin
   -- Recupera os dados da solicitação.
   select reembolso into w_reembolso from pd_missao where sq_siw_solicitacao = p_chave;
   
   -- Recupera a chave do cliente
   select sq_pessoa into w_cliente from siw_menu where sq_menu = p_menu;

   -- Recupera o trâmite para o qual está sendo enviada a solicitação
   If p_devolucao = 'N' Then
      -- Decide para qual trâmite irá enviar
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from siw_tramite a
        where sq_siw_tramite = p_tramite;
  
      If w_sg_tramite in ('CI','PC') Then
         -- Calcula as quantidades de diárias
         SP_CalculaDiarias(p_chave,null);
         
         If w_sg_tramite = 'CI' Then
            -- Se cadastramento inicial
            select count(*) into w_existe
              from pd_deslocamento        a
                   inner   join co_cidade b on (a.destino = b.sq_cidade)
                     inner join co_pais   c on (b.sq_pais = c.sq_pais and c.padrao = 'N')
             where a.sq_siw_solicitacao = p_chave
               and a.tipo               = case w_sg_tramite when 'CI' then 'S' else 'P' end;
           
            -- Se viagem para exterior, vai para a cotação de preços; senão vai para aprovação
            If w_existe > 0 
               Then w_salto := 1;
               Else w_salto := 2;
            End If;
         Else
            w_salto := 1;
         End If;
      Elsif w_sg_tramite = 'EA' Then
         -- ABDI: Se análise pela DIREX, decide o salto se o usuário for da presidência ou do gabinete
         select (x.qtd + y.qtd) into w_existe
           from (select count(*) as qtd
                   from pd_missao                    a
                        inner   join sg_autenticacao b on (a.sq_pessoa          = b.sq_pessoa)
                          inner join eo_unidade      c on (b.sq_unidade         = c.sq_unidade)
                        inner   join siw_solicitacao d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
                          inner join siw_menu        e on (d.sq_menu            = e.sq_menu)
                  where a.sq_siw_solicitacao = p_chave
                    and e.sq_pessoa          = w_cliente
                    and (c.sq_unidade_pai    is null or c.sigla = 'GABINETE')
                ) x,
                (select count(*) as qtd
                   from siw_solicitacao              d
                        inner   join sg_autenticacao b on (d.solicitante        = b.sq_pessoa)
                          inner join eo_unidade      c on (b.sq_unidade         = c.sq_unidade)
                          inner join siw_menu        e on (d.sq_menu            = e.sq_menu)
                  where d.sq_siw_solicitacao = p_chave
                    and e.sq_pessoa          = w_cliente
                    and (c.sq_unidade_pai    is null or c.sigla = 'GABINETE')
                ) y;
        
         If w_existe > 0 
            Then w_salto := 1;
            Else w_salto := 2;
         End If;
      Elsif w_sg_tramite = 'VP' Then
         -- ABDI: Se tiver reembolso, vai para o próximo trâmite. Senão, pula para o trâmite seguinte.
         If w_reembolso = 'S'
            Then w_salto := 1;
            Else w_salto := 3;
         End If;
      Else
         w_salto := 1;
      End If;
      
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.ordem   = (select ordem+w_salto from siw_tramite where sq_siw_tramite = p_tramite);
   Else
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from siw_tramite a
        where a.sq_siw_tramite = p_novo_tramite;   
   End If;
   
   -- Recupera a próxima chave
   select sq_siw_solic_log.nextval into w_chave from dual;
    
   -- Se houve mudança de fase, grava o log
   Insert Into siw_solic_log 
       (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
        sq_siw_tramite,            data,               devolucao, 
        observacao
       )
   (Select 
        w_chave,                   p_chave,            p_pessoa,
        p_tramite,                 sysdate,            p_devolucao,
        case p_devolucao when 'S' then 'Devolução da fase "' else 'Envio da fase "' end ||a.nome||'" '||
        ' para a fase "'||b.nome||'".'
       from siw_tramite a,
            siw_tramite b
      where a.sq_siw_tramite = p_tramite
        and b.sq_siw_tramite = w_tramite
   );

   Update siw_solicitacao set
      sq_siw_tramite        = w_tramite,
      conclusao             = null,
      justificativa         = coalesce(p_justificativa, justificativa)
   Where sq_siw_solicitacao = p_chave;

   -- Atualiza o situacao da demanda para não concluída
   Update gd_demanda set 
      concluida      = 'N',
      inicio_real    = null,
      fim_real       = null,
      data_conclusao = null, 
      custo_real     = 0
   Where sq_siw_solicitacao = p_chave;

   If p_justif_dia_util is not null Then
      -- Atualiza a justificativa para viagem contendo fim de semana/feriado
      Update pd_missao a set 
         a.justificativa_dia_util = p_justif_dia_util
      Where sq_siw_solicitacao = p_chave;
   End If;

   -- Se um despacho foi informado, insere em GD_DEMANDA_LOG.
   If p_despacho is not null Then
      -- Recupera a nova chave da tabela de encaminhamentos da demanda
      select sq_demanda_log.nextval into w_chave_dem from dual;
       
      -- Insere registro na tabela de encaminhamentos da demanda
      Insert into gd_demanda_log 
         (sq_demanda_log,            sq_siw_solicitacao, cadastrador, 
          destinatario,              data_inclusao,      observacao, 
          despacho,                  sq_siw_solic_log
         )
      Values (
          w_chave_dem,               p_chave,            p_pessoa,
          null,                      sysdate,            null,
          p_despacho,                w_chave
       );
   End If;
   
   If p_devolucao = 'N' Then
      If w_sg_tramite = 'PC' or (w_sg_tramite = 'EE' and w_reembolso = 'S') Then
          -- Cria/atualiza lançamento financeiro
         for crec in c_financeiro_geral loop
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
                               p_sq_tipo_lancamento => crec.sq_lancamento,
                               p_sq_forma_pagamento => crec.sq_forma_pagamento,
                               p_sq_tipo_pessoa     => 1, -- pessoa física
                               p_tipo_rubrica       => 5, -- despesas
                               p_per_ini            => crec.inicio,
                               p_per_fim            => crec.fim,
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
             For drec in c_financeiro_item Loop
                 i := i + 1;
                 sp_putlancamentoitem(
                               p_operacao           => 'I',
                               p_chave              => w_sq_doc,
                               p_chave_aux          => null,
                               p_sq_projeto_rubrica => drec.sq_rubrica,
                               p_descricao          => drec.nm_despesa||': '||drec.sg_moeda||' '||fValor(drec.valor,'T'),
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
                                 p_despacho      => case w_sg_tramite 
                                                         when 'EE' 
                                                         then 'Envio automático de reembolso de viagem.' 
                                                         else 'Envio automático de adiantamento de diárias.' 
                                                    end
                                );
             End If;
         End Loop;
      End If;
   End If;
   commit;
      
end SP_PutViagemEnvio;
/
