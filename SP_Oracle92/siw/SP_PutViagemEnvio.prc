create or replace procedure SP_PutViagemEnvio
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_novo_tramite        in number    default null,    
    p_devolucao           in varchar2,
    p_despacho            in varchar2,
    p_justificativa       in varchar2
   ) is
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
   
   cursor c_missao is
      select * from pd_missao where sq_siw_solicitacao = p_chave;
      
   cursor c_financeiro_geral is
      select x.codigo_interno as cd_interno, w.sq_pessoa as cliente, w.sq_menu, w.sq_unid_executora, 
             'Adiantamento de diárias da '||x.codigo_interno||'.' as descricao,
             soma_dias(10135,trunc(sysdate),2,'U') as vencimento, 
             w1.sq_cidade_padrao as sq_cidade, x.sq_siw_solicitacao as sq_solic_pai, 
             'Registro gerado automaticamente pelo sistema de viagens' as observacao, z.sq_lancamento, 
             x1.sq_forma_pagamento, x.inicio, x.fim, y.sq_tipo_documento,
             x2.sq_siw_solicitacao as sq_financeiro,
             x4.sq_lancamento_doc  as sq_documento
        from siw_menu                         w
             inner join siw_cliente           w1 on (w.sq_pessoa           = w1.sq_pessoa),
             siw_solicitacao                  x
             inner     join pd_missao         x1 on (x.sq_siw_solicitacao  = x1.sq_siw_solicitacao)
             left      join siw_solicitacao   x2 on (x.sq_siw_solicitacao  = x2.sq_solic_pai)
               left    join fn_lancamento     x3 on (x2.sq_siw_solicitacao = x3.sq_siw_solicitacao)
                 left  join fn_lancamento_doc x4 on (x3.sq_siw_solicitacao = x4.sq_siw_solicitacao),
             fn_tipo_documento             y,
             (select sq_tipo_lancamento as sq_lancamento, nm_lancamento
                from (select c2.sq_tipo_lancamento, c2.nome as nm_lancamento
                        from siw_solicitacao                      a
                             inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                             inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                               inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                                 inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       where a.sq_siw_solicitacao = p_chave
                      UNION
                      select c2.sq_tipo_lancamento, c2.nome as nm_lancamento
                        from siw_solicitacao                      a
                             inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                             inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                               inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_hospedagem    = c.sq_pdvinculo_financeiro)
                                 inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       where a.sq_siw_solicitacao = p_chave
                      UNION
                      select c2.sq_tipo_lancamento, c2.nome as nm_lancamento
                        from siw_solicitacao                      a
                             inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                             inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                               inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                                 inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       where a.sq_siw_solicitacao = p_chave
                     )
             )                             z
       where w.sigla              = 'FNDVIA'
         and x.sq_siw_solicitacao = p_chave
         and y.sigla              = 'VG'
         and (x3.sq_siw_solicitacao is null or (x3.sq_siw_solicitacao is not null and x3.sq_tipo_lancamento = z.sq_lancamento));

   cursor c_financeiro_item is
      select sq_projeto_rubrica as sq_rubrica, cd_rubrica, nm_rubrica, sq_diaria, sg_moeda, nm_moeda, sb_moeda, sum(valor) as valor
        from (select 'DIA' as tp_despesa, b.sq_diaria, (b.quantidade*b.valor) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria            = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_chave
              UNION
              select 'HSP' as tp_despesa, b.sq_diaria, (b.hospedagem_qtd*b.hospedagem_valor) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_hospedagem    = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_hospedagem = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_chave
              UNION
              select 'VEI' as tp_despesa, b.sq_diaria, (-1*b.valor*b.veiculo_qtd*b.veiculo_valor/100) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_veiculo    = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_chave
             )
      group by sq_projeto_rubrica, cd_rubrica, nm_rubrica, sq_diaria, sg_moeda, nm_moeda, sb_moeda;
   
begin
   -- Recupera o trâmite para o qual está sendo enviada a solicitação
   If p_devolucao = 'N' Then
      -- Decide para qual trâmite irá enviar
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from siw_tramite a
        where sq_siw_tramite = p_tramite;
  
      If w_sg_tramite = 'CI' Then
         -- Se cadastramento inicial
         select count(*) into w_existe
           from pd_deslocamento        a
                inner   join co_cidade b on (a.destino = b.sq_cidade)
                  inner join co_pais   c on (b.sq_pais = c.sq_pais and c.padrao = 'N')
          where a.sq_siw_solicitacao = p_chave;
        
         -- Se viagem para exterior, vai para a cotação de preços; senão vai para aprovação
         If w_existe > 0 
            Then w_salto := 1;
            Else w_salto := 2;
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
      nota_conclusao = null, 
      custo_real     = 0
   Where sq_siw_solicitacao = p_chave;

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
      If w_sg_tramite = 'EE' Then
         -- Verifica necessidade de gerar financeiro para o adiantamento de diárias
         select count(*) into w_existe
           from siw_solicitacao     a
                inner join siw_menu b on (a.sq_menu = b.sq_menu and b.sigla = 'FNDVIA')
          where a.sq_solic_pai = p_chave;
          
         -- Decide a operação a ser executada
         If w_existe = 0 Then w_operacao := 'I'; Else w_operacao := 'A'; End If;

          -- Cria/atualiza lançamento financeiro
         for crec in c_financeiro_geral loop
             sp_putfinanceirogeral(
                               p_operacao           => w_operacao,
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
                               p_operacao           => w_operacao,
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
                               p_descricao          => 'Pagamento de adiantamento de diárias: '||drec.sg_moeda||' '||fValor(drec.valor,'T'),
                               p_quantidade         => 1,
                               p_valor_unitario     => drec.valor,
                               p_ordem              => i
                              );
             End Loop;

             If w_operacao = 'I' Then
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
                                 p_despacho      => 'Envio automático de adiantamento de diárias.'
                                );
             End If;
         End Loop;
      End If;
   End If;
   commit;
      
end SP_PutViagemEnvio;
/
