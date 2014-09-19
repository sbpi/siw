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
   i               number(18);
   w_ci            number(18);
   w_ee            number(18);
   w_pp            number(18);
   w_salto         number(4)    := 0;
   w_passagem      pd_missao.passagem%type;
   w_reembolso     pd_missao.reembolso%type;
   w_ressarcimento pd_missao.ressarcimento%type;
   w_complemento   pd_missao.complemento_valor%type;
   w_conta         co_pessoa_conta%rowtype;
   w_beneficiario  number(18);
   w_benef_contr   co_tipo_vinculo.contratado%type;
   w_unidade_aprov eo_unidade.sq_unidade%type;
   w_especial      number(18);
   w_pendencia     number(18) := 0;
   
   cursor c_missao is
      select * from pd_missao where sq_siw_solicitacao = p_chave;
      
   cursor c_financeiro_pendente (l_cliente in number, l_pessoa in number) is
      select x.codigo_interno as cd_viagem, w.sq_siw_solicitacao, w2.sq_menu
        from fn_lancamento                  w
             inner     join siw_solicitacao w1 on (w.sq_siw_solicitacao  = w1.sq_siw_solicitacao)
               inner   join siw_menu        w2 on (w1.sq_menu            = w2.sq_menu and 
                                                   w2.sigla              = 'FNDVIA' and
                                                   w2.sq_pessoa          = l_cliente
                                                  )
               inner   join siw_tramite     w3 on (w1.sq_siw_tramite     = w3.sq_siw_tramite and
                                                   w3.sigla              = 'PP'
                                                  ),
             siw_solicitacao                x
       where w.pessoa             = l_pessoa
         and x.sq_siw_solicitacao = p_chave;
      
   cursor c_reembolso is
      select x.codigo_interno as cd_interno, w.sq_pessoa as cliente, w.sq_menu, w.sq_unid_executora, 
             case x5.sigla when 'EE' then 'Reembolso da ' else 'Adiantamento de diárias da 'end ||x.codigo_interno||' ('||x4.nm_moeda||') '||fValor(x4.valor,'T') as descricao,
             soma_dias(w_cliente,trunc(sysdate),2,'U') as vencimento, 
             w1.sq_cidade_padrao as sq_cidade, x.sq_siw_solicitacao as sq_solic_pai, 
             'Registro gerado automaticamente pelo sistema de viagens' as observacao, z.sq_lancamento, 
             coalesce(x1.sq_forma_pagamento, w2.sq_forma_pagamento) as sq_forma_pagamento, x.inicio, x.fim, y.sq_tipo_documento,
             x2.sq_financeiro, x2.sq_lancamento_doc  as sq_documento, coalesce(x2.sg_tramite,'-') as sg_tramite,
             x3.sq_tipo_pessoa,
             x4.sq_rubrica, x4.cd_rubrica, x4.nm_rubrica, x4.sq_moeda, x4.sg_moeda, x4.nm_moeda, x4.sb_moeda, x4.valor
        from siw_menu                          w
             inner     join siw_cliente        w1 on (w.sq_pessoa           = w1.sq_pessoa)
               inner   join co_forma_pagamento w2 on (w1.sq_pessoa          = w2.cliente and w2.sigla = 'CREDITO'),
             siw_solicitacao                   x
             inner     join siw_tramite       x5 on (x.sq_siw_tramite      = x5.sq_siw_tramite)
             inner     join pd_missao         x1 on (x.sq_siw_solicitacao  = x1.sq_siw_solicitacao and
                                                     (x1.reembolso         = 'S' or
                                                      x5.sigla             <> 'EE'
                                                     )
                                                    )
               inner   join co_pessoa         x3 on (x1.sq_pessoa          = x3.sq_pessoa)
               inner   join (select sq_siw_solicitacao, 
                                    sq_projeto_rubrica as sq_rubrica, cd_rubrica, nm_rubrica, sq_moeda, sg_moeda, nm_moeda, sb_moeda, sum(valor) as valor
                               from (select distinct a.sq_siw_solicitacao, 'CMP' as tp_despesa, null as sq_diaria, a1.complemento_valor as valor,
                                            c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                                            d1.sq_moeda, d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                                       from siw_solicitacao                      a
                                            inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla <> 'EE')
                                            inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao and
                                                                                        a1.complemento_valor         > 0
                                                                                       )
                                              inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_bilhete      = c.sq_pdvinculo_financeiro)
                                                inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                                              inner   join co_moeda              d1 on (a1.sq_moeda_complemento      = d1.sq_moeda)
                                      where a.sq_siw_solicitacao = p_chave
                                     UNION
                                     select a.sq_siw_solicitacao, 'RMB' as tp_despesa, null as sq_diaria, b.valor_autorizado as valor,
                                            c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                                            b1.sq_moeda, b1.sigla as sg_moeda, b1.nome as nm_moeda, b1.simbolo as sb_moeda
                                       from siw_solicitacao                      a
                                            inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla = 'EE')
                                            inner     join pd_reembolso          b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                                              inner   join co_moeda              b1 on (b.sq_moeda                   = b1.sq_moeda)
                                            inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                                              inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                                                inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                                      where a.sq_siw_solicitacao = p_chave
                                     UNION
                                     select a.sq_siw_solicitacao, 'DIA' as tp_despesa, b.sq_diaria, (b.quantidade*b.valor) as valor,
                                            c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                                            d1.sq_moeda, d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                                       from siw_solicitacao                      a
                                            inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla <> 'EE')
                                            inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                                            inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                                              inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                                                inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                                              inner   join pd_valor_diaria       d  on (b.sq_valor_diaria            = d.sq_valor_diaria)
                                                inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
                                      where a.sq_siw_solicitacao = p_chave
                                        and b.tipo               = case a2.sigla when 'EE' then 'P' else 'S' end
                                     UNION
                                     select a.sq_siw_solicitacao, 'VEI' as tp_despesa, b.sq_diaria, (-1*b.valor*b.veiculo_qtd*b.veiculo_valor/100) as valor,
                                            c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                                            d1.sq_moeda, d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                                       from siw_solicitacao                      a
                                            inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla <> 'EE')
                                            inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                                            inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                                              inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                                                inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                                              inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_veiculo    = d.sq_valor_diaria)
                                                inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
                                      where a.sq_siw_solicitacao = p_chave
                                        and b.tipo               = case a2.sigla when 'EE' then 'P' else 'S' end
                                    ) k
                             group by sq_siw_solicitacao, sq_projeto_rubrica, cd_rubrica, nm_rubrica, sq_moeda, sg_moeda, nm_moeda, sb_moeda
                            )                 x4 on (x.sq_siw_solicitacao  = x4.sq_siw_solicitacao)
             left      join (select a.sq_siw_solicitacao as sq_financeiro, a.sq_solic_pai, a.descricao, c.sq_tipo_lancamento, d.sq_lancamento_doc,
                                    b.sigla as sg_tramite
                               from siw_solicitacao                a
                                    inner   join siw_tramite       b on (a.sq_siw_tramite     = b.sq_siw_tramite and b.sigla <> 'CA')
                                    inner   join fn_lancamento     c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
                                      inner join fn_lancamento_doc d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
                            )                 x2 on (x.sq_siw_solicitacao  = x2.sq_solic_pai and
                                                     0                     < instr(x2.descricao,'('||x4.nm_moeda||')') and 
                                                     (x5.sigla <> 'EE' or (x5.sigla = 'EE' and instr(lower(x2.descricao),'adiantamento')=0))
                                                    ),
             fn_tipo_documento             y,
             (select sq_tipo_lancamento as sq_lancamento, nm_lancamento
                from (select c2.sq_tipo_lancamento, c2.nome as nm_lancamento
                        from siw_solicitacao                      a
                             inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite and a2.sigla <> 'EE')
                             inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao and
                                                                         a1.complemento_valor         > 0
                                                                        )
                             inner     join pd_vinculo_financeiro c  on (a.sq_solic_pai               = c.sq_siw_solicitacao and
                                                                         c.diaria                     = 'S'
                                                                        )
                               inner   join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       where a.sq_siw_solicitacao = p_chave
                      UNION
                      select c2.sq_tipo_lancamento, c2.nome as nm_lancamento
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
                     ) k
             )                             z
       where w.sigla              = 'FNDVIA'
         and x.sq_siw_solicitacao = p_chave
         and y.sigla              = 'VG'
         and z.sq_lancamento      = case when x2.sq_financeiro is null then z.sq_lancamento else x2.sq_tipo_lancamento end
         and x4.valor             > 0;

   cursor c_ressarcimento_geral is
      select x.codigo_interno as cd_interno, x.sq_moeda, w.sq_pessoa as cliente, w.sq_menu, w.sq_unid_executora, 
             'Devolução de valores da '||x.codigo_interno||'.' as descricao,
             soma_dias(w_cliente,trunc(sysdate),2,'U') as vencimento, 
             w1.sq_cidade_padrao as sq_cidade, x.sq_siw_solicitacao as sq_solic_pai, 
             'Registro gerado automaticamente pelo sistema de viagens' as observacao,
             coalesce(x1.sq_forma_pagamento, w2.sq_forma_pagamento) as sq_forma_pagamento, x.inicio, x.fim, y.sq_tipo_documento,
             x2.sq_financeiro, x2.sq_lancamento_doc  as sq_documento, z.sq_tipo_lancamento, z.nm_lancamento,
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
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_ressarcimento = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica          = c1.sq_projeto_rubrica),
                     co_moeda                             b
               where a.sq_siw_solicitacao = p_chave
                 and b.sigla              = 'BRL'
              ) k
      group by sq_projeto_rubrica, cd_rubrica, nm_rubrica, sg_moeda, nm_moeda, sb_moeda, tp_despesa;
   
begin
   -- Recupera os dados da solicitação. Se for contratado, a unidade solicitante aprova trâmites de chefia imediata. Caso contrário, será a unidade proponente.
   select a.reembolso, a.ressarcimento, a.complemento_valor, a.sq_pessoa,
          c.contratado, case c.contratado 
                             when 'S' then coalesce(a2.sq_unidade_exercicio, a1.sq_unidade, d.sq_unidade)
                             else e.sq_unidade_resp 
                        end
     into w_reembolso, w_ressarcimento, w_complemento,       w_beneficiario, 
          w_benef_contr, w_unidade_aprov
     from pd_missao                             a
          left    join sg_autenticacao         a1 on (a.sq_pessoa          = a1.sq_pessoa)
          left    join gp_contrato_colaborador a2 on (a.sq_pessoa          = a2.sq_pessoa and a2.fim is null)
          inner   join co_pessoa                b on (a.sq_pessoa          = b.sq_pessoa)
            inner join co_tipo_vinculo          c on (b.sq_tipo_vinculo    = c.sq_tipo_vinculo)
          inner   join siw_solicitacao          d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
          inner   join gd_demanda               e on (a.sq_siw_solicitacao = e.sq_siw_solicitacao)
    where a.sq_siw_solicitacao = p_chave;
   
   -- Recupera a chave do cliente
   select sq_pessoa into w_cliente from siw_menu where sq_menu = p_menu;
   
   -- Verifica se é necessária a aquisição de passagens
   select case count(*) when 0 then 'N' else 'S' end
     into w_passagem
     from pd_deslocamento a
    where sq_siw_solicitacao = p_chave
      and a.tipo             = 'S'
      and a.passagem         = 'S';

   -- Recupera o trâmite para o qual está sendo enviada a solicitação
   If p_devolucao = 'N' Then
      -- Decide para qual trâmite irá enviar
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from siw_tramite a
        where sq_siw_tramite = p_tramite;
  
      -- Verifica se há pendência na prestação de contas de alguma viagem
      select count(*) into w_pendencia
        from pd_missao                        a
             inner   join pd_categoria_diaria f on (a.diaria              = f.sq_categoria_diaria)
             inner   join siw_solicitacao     b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
               inner join siw_tramite         c on (b.sq_siw_tramite      = c.sq_siw_tramite and
                                                    c.sigla               in ('PC','AP')
                                                   )
               inner join siw_menu            d on (b.sq_menu             = d.sq_menu)
               inner join pd_parametro        e on (d.sq_pessoa           = e.cliente)
       where 0           > soma_dias(e.cliente,trunc(b.fim),f.dias_prestacao_contas + 1,'U') - trunc(sysdate)
         and p_chave    <> a.sq_siw_solicitacao
         and a.sq_pessoa = w_beneficiario;

      -- Se o trâmite for de chefia imediata pula para o próximo se:
      -- a) é titular da unidade solicitante e beneficiário da viagem
      -- b) é titular da unidade proponente e o beneficiário não é contratado pela organização 
      select count(*) into w_existe
        from (-- Usuário logado é chefe da unidade proponente ou da unidade solicitante
              select 1
                from siw_solicitacao        b
                     inner join siw_tramite d on (b.sq_siw_tramite     = d.sq_siw_tramite),
                     eo_unidade_resp        c
               where b.sq_siw_solicitacao = p_chave
                 and d.ordem              < (select ordem from siw_tramite where sq_menu = b.sq_menu and sigla = 'CH')
                 and c.sq_unidade         = w_unidade_aprov
                 and c.sq_pessoa          = case w_benef_contr when 'S' then w_beneficiario else p_pessoa end
                 and c.tipo_respons       = 'T'
                 and c.fim                is null
              UNION
              /*
              -- Beneficiário da viagem é chefe da unidade solicitante
              select 1
                from siw_solicitacao               b
                     inner   join siw_tramite      d on (b.sq_siw_tramite     = d.sq_siw_tramite)
                     inner   join pd_missao        e on (b.sq_siw_solicitacao = e.sq_siw_solicitacao)
                       inner join eo_unidade_resp  c on (e.sq_pessoa          = c.sq_pessoa and
                                                         c.tipo_respons       = 'T' and
                                                         c.fim                is null
                                                        )
               where b.sq_siw_solicitacao = p_chave
                 and d.ordem              < (select ordem from siw_tramite where sq_menu = b.sq_menu and sigla = 'CH')
                 and c.sq_unidade         = w_unidade_aprov
              UNION
              */
              select 1
                from siw_solicitacao                b
                     inner     join siw_tramite     d on (b.sq_siw_tramite     = d.sq_siw_tramite)
                     inner     join siw_solic_log   f on (b.sq_siw_solicitacao = f.sq_siw_solicitacao)
                       inner   join (select w.sq_siw_solic_log, max(w.data) as data
                                       from siw_solic_log          w
                                            inner join siw_tramite x on (w.sq_siw_tramite = x.sq_siw_tramite and x.sigla = 'CI')
                                      where w.sq_siw_solicitacao = p_chave
                                        and w.observacao         like 'Envio%'
                                        and w.devolucao          = 'N'
                                     group by w.sq_siw_solic_log
                                    )               g on (f.sq_siw_solic_log   = g.sq_siw_solic_log and
                                                          f.data               = g.data
                                                         )
                     inner     join eo_unidade_resp c on (c.sq_unidade         = w_unidade_aprov and
                                                          c.sq_pessoa          = f.sq_pessoa and
                                                          c.tipo_respons       = 'T' and
                                                          c.fim                is null
                                                         )
               where b.sq_siw_solicitacao = p_chave
                 and d.ordem              < (select ordem from siw_tramite where sq_menu = b.sq_menu and sigla = 'CH')
                 and d.ordem              > 1
             ) k;
      -- Se sim, pula autorização pelo chefe imediato.
      If w_existe > 0 
         Then w_salto := 1;
         Else w_salto := 0;
      End If;

      -- Se o trâmite for de chefia imediata e a categoria de diárias tiver tramitação especial, pula para o próximo
      select count(*) into w_existe
        from pd_missao                        a
             inner   join pd_categoria_diaria b on (a.diaria             = b.sq_categoria_diaria and
                                                    b.tramite_especial   = 'S'
                                                   )
             inner   join siw_solicitacao     d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
               inner join siw_tramite         e on (d.sq_siw_tramite     = e.sq_siw_tramite)
       where a.sq_siw_solicitacao = p_chave
         and e.ordem              < (select ordem from siw_tramite where sq_menu = d.sq_menu and sigla = 'CH');
      -- Se sim, pula autorização pelo chefe imediato.
      If w_existe > 0 or w_salto = 1
         Then w_salto := 1;
         Else w_salto := 0;
      End If;

      If w_sg_tramite in ('CI','PC') Then
         -- Calcula as quantidades de diárias
         SP_CalculaDiarias(p_chave,null);
         
         If w_sg_tramite = 'CI' and w_cliente = 10135 Then
            -- Se cadastramento inicial e ABDI
            select count(*) into w_existe
              from pd_deslocamento        a
                   inner   join co_cidade b on (a.destino = b.sq_cidade)
                     inner join co_pais   c on (b.sq_pais = c.sq_pais and c.padrao = 'N')
             where a.sq_siw_solicitacao = p_chave
               and a.tipo               = case w_sg_tramite when 'CI' then 'S' else 'P' end;
           
            -- Se viagem para exterior, vai para a cotação de preços; senão vai para aprovação
            If w_existe > 0 
               Then w_salto := 1;
               Else w_salto := w_salto + 2;
            End If;
         Elsif w_sg_tramite = 'CI' and w_cliente = 17305 Then
            -- O trãmite após o de cadastramento é o de cotação de passagens (DF).
            -- Se for indicado que não haverá despesas com passagens, salta esse trâmite.
            If w_passagem = 'N' 
               Then w_salto := 2;
               Else w_salto := 1;
            End If;
         Else
            w_salto := 1;
         End If;
      Elsif w_cliente = 10135 and (w_sg_tramite = 'EA' or w_sg_tramite = 'DA') Then
         w_especial := 0;
         If w_sg_tramite = 'EA' Then
            -- ABDI: Se viagem internacional, exige trâmite de autorização complementar pela DIREX; caso contrário, salta esse trâmite
            select count(*) into w_existe
              from pd_missao                        a
             where a.sq_siw_solicitacao = p_chave
               and a.internacional      = 'S';
           
            w_salto := w_salto + 1; 
            If w_existe > 0 Then 
               w_especial := 1;
            End If;
         End If;

         If w_especial = 0 Then
            -- ABDI: Se análise pela DIREX, decide o salto dependendo da categoria de diária
            select count(*) into w_existe
              from pd_missao                        a
                   inner   join pd_categoria_diaria b on (a.diaria             = b.sq_categoria_diaria and
                                                          b.tramite_especial   = 'S'
                                                         )
                   inner   join siw_solicitacao     d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
                     inner join siw_menu            e on (d.sq_menu            = e.sq_menu)
             where a.sq_siw_solicitacao = p_chave
               and e.sq_pessoa          = w_cliente;
           
            If w_existe > 0 
               Then w_salto := w_salto + 1;
               Else w_salto := w_salto + 2;
            End If;
         End If;
      Elsif w_cliente in (10135, 17305) and w_sg_tramite = 'VP' Then
         If w_reembolso = 'S' Then        -- ABDI/OTCA: Se tiver reembolso, vai para aprovação da GERPE.
            w_salto := w_salto + 1;
         Elsif w_ressarcimento = 'S' Then -- ABDI/OTCA: Se tiver ressarcimento, vai para envio à GERAF.
            w_salto := w_salto + 2;
         Else                             -- ABDI/OTCA: Senão, vai para arquivamento.
            w_salto := w_salto + 3;
         End If;

         -- Libera pagamentos pendentes de prestação de contas se não houver pendência
         If w_pendencia = 0 Then
            for crec in c_financeiro_pendente (w_cliente, w_beneficiario) loop
               select sq_siw_tramite into w_ee from siw_tramite where sq_menu = crec.sq_menu and sigla='EE';
               select sq_siw_tramite into w_pp from siw_tramite where sq_menu = crec.sq_menu and sigla='PP';
                   
               sp_putlancamentoenvio(
                                p_menu          => crec.sq_menu,
                                p_chave         => crec.sq_siw_solicitacao,
                                p_pessoa        => p_pessoa,
                                p_tramite       => w_pp,
                                p_novo_tramite  => w_ee,
                                p_devolucao     => 'N',
                                p_despacho      => 'Pagamento desbloqueado em função da realização da prestação de contas '||crec.cd_viagem||'.'
                               );
            end loop;   
         End If;

      Else
         w_salto := w_salto + 1;
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
      If w_sg_tramite = 'PC' or (w_sg_tramite = 'EE' and (w_reembolso = 'S' or w_ressarcimento = 'S' or w_complemento > 0)) Then
         If w_sg_tramite = 'PC' or w_reembolso = 'S' or w_complemento > 0 Then
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
                                    p_novo_tramite  => case when w_pendencia > 0 and crec.descricao like 'Adiantamento%' then w_pp else w_ee end,
                                    p_devolucao     => 'N',
                                    p_despacho      => case w_sg_tramite 
                                                            when 'EE' 
                                                            then 'Envio automático de reembolso de viagem.' 
                                                            else 'Envio automático de adiantamento de diárias.' 
                                                       end
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
                                  p_moeda              => crec.sq_moeda,
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
   End If;
   commit;
      
end SP_PutViagemEnvio;
/
