create or replace procedure SP_PutSolicEnvio
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_novo_tramite        in number    default null,    
    p_devolucao           in varchar2,
    p_despacho            in varchar2,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2  default null
   ) is
   w_chave         number(18) := null;
   w_chave_arq     number(18) := null;
   w_tramite       number(18);
   w_sg_tramite    siw_tramite.sigla%type;
   w_or_tramite    siw_tramite.ordem%type;
   w_menu          siw_menu%rowtype;
   w_cont          number(4);
   w_solic         siw_solicitacao%rowtype;
   w_solic_cl      cl_solicitacao%rowtype;
   w_sq_modal      lc_modalidade.sq_lcmodalidade%type;
   w_min_pesq      lc_modalidade.minimo_pesquisas%type;

begin
   -- Recupera os dados do serviço
   select * into w_menu from siw_menu where sq_menu = p_menu;
   
   -- Se houve mudança no trâmite atual, recupera o trâmite para o qual está sendo enviada a solicitação
   If p_tramite <> nvl(p_novo_tramite, 0) Then
      If p_devolucao = 'N' Then
         select sq_siw_tramite, sigla, ordem into w_tramite, w_sg_tramite, w_or_tramite
            from siw_tramite a
           where a.sq_menu = p_menu
             and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = p_tramite);
         
         -- Recupera os dados da solicitação
         select * into w_solic from siw_solicitacao where sq_siw_solicitacao = p_chave;

         -- Recupera os dados da solicitação de compra
         select count(*) into w_cont from cl_solicitacao where sq_siw_solicitacao = p_chave;
         If w_cont > 0 Then
            select * into w_solic_cl from cl_solicitacao where sq_siw_solicitacao = p_chave;
         End If;

         -- Decide a tramitação de pedidos de compra
         If w_menu.sq_pessoa = 10135 and substr(w_menu.sigla,1,4)='CLPC' Then
            -- Regras da ABDI: 
            --   Pedidos até R$ 5.000,00 não passam pelo gabinete; vão direto para análise pela GERAF.
            --   Compra por fundo fixo vai para trâmite especial de compra pela GERAF.
            If w_or_tramite = 3 Then
               If w_solic.valor <= 5000 Then
                  select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                     from siw_tramite a
                    where a.sq_menu = p_menu
                      and a.sigla   = 'AF';
               End If;
            Elsif w_or_tramite = 5 and coalesce(w_solic_cl.fundo_fixo,'N') = 'N' Then
               select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                  from siw_tramite a
                 where a.sq_menu = p_menu
                   and a.sigla   = 'EE';
            End If;
         Elsif w_menu.sq_pessoa = 10135 and substr(w_menu.sigla,1,4)='CLLC' and w_sg_tramite = 'PP' Then
            -- Regra da ABDI: 
            --   Se a modalidade estiver indicada e não exigir pesquisas de preço, pula para o próximo trâmite
            select count(*) into w_cont
              from cl_solicitacao           a
                   inner join lc_modalidade b on (a.sq_lcmodalidade = b.sq_lcmodalidade)
             where a.sq_siw_solicitacao = p_chave
               and b.minimo_pesquisas   = 0;
            
            If w_cont > 0 Then
               select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                  from siw_tramite a
                 where a.sq_menu = p_menu
                   and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = w_tramite);
            End If;
         Elsif w_menu.sq_pessoa = 10135 and w_menu.sigla='SRSERVGER' and w_sg_tramite = 'AA' and coalesce(w_solic.valor,0) = 0 Then
            -- Regra da ABDI: 
            --   Solicitações de serviço geral com valores maior que 0 devem ser autorizadas pela GERAF; 
            --   caso contrário, passam direto para execução
            select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
               from siw_tramite a
              where a.sq_menu = p_menu
                and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = w_tramite);
         Elsif w_menu.sq_pessoa = 10135 and w_menu.sigla='FNDREEMB' and w_sg_tramite = 'EE' Then
            -- Regra da ABDI: 
            --   Quando reembolso é autorizado para pagamento, vencimento deve ser dois dias úteis após o dia atual
            Update siw_solicitacao set
               fim            = soma_dias(w_menu.sq_pessoa, trunc(sysdate), 2, 'U'),
               conclusao      = null
            Where sq_siw_solicitacao = p_chave;

            -- Atualiza a situação do lançamento financeiro
            Update fn_lancamento 
               set quitacao        = null,
                   vencimento      = soma_dias(w_menu.sq_pessoa, trunc(sysdate), 2, 'U'),
                   sq_pessoa_conta = null
            Where sq_siw_solicitacao = p_chave;
         Elsif w_sg_tramite = 'PP' and w_menu.sigla = 'FNDVIA' Then
            -- Pagamento de diária de beneficiário sem pendência na prestação de contas vai salta para o trâmite EE (Pagamento)
            select count(*) into w_cont
              from pd_missao                        a
                   inner   join pd_categoria_diaria f on (a.diaria              = f.sq_categoria_diaria)
                   inner   join siw_solicitacao     b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                     inner join siw_tramite         c on (b.sq_siw_tramite      = c.sq_siw_tramite and
                                                          c.sigla               in ('PC','AP')
                                                         )
                     inner join siw_menu            d on (b.sq_menu             = d.sq_menu)
                     inner join pd_parametro        e on (d.sq_pessoa           = e.cliente)
             where 0           > soma_dias(e.cliente,trunc(b.fim),f.dias_prestacao_contas + 1,'U') - trunc(sysdate)
               and 0           < (select count(*)
                                    from siw_solicitacao        w
                                         inner join siw_tramite x on (w.sq_menu = x.sq_menu)
                                   where w.sq_siw_solicitacao = p_chave
                                     and x.sigla              = 'PP'
                                  )
               and a.sq_pessoa = (select pessoa from fn_lancamento where sq_siw_solicitacao = p_chave);

            -- Se não houver pendência, coloca o lançamento na fase de pagamento (última antes de estar concluída).
            If w_cont = 0 Then
               select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                  from siw_tramite a
                 where a.sq_menu = p_menu
                   and a.ordem   = (select max(ordem) from siw_tramite where sq_menu = p_menu and ativo = 'S');
            End If;
         Elsif w_sg_tramite = 'PP' and substr(w_menu.sigla,1,4)='CLRP' Then
            -- Se o trâmite for de pesquisa de preços de pedido de ARP e tiver o número necessário de pesquisas, pula para o próximo.
            select count(*)
              into w_cont
              from (select a.sq_solicitacao_item, coalesce(i.qtd_cotacao,0) as qtd
                      from cl_solicitacao_item a
                           left join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_cotacao
                                        from siw_solicitacao                  x
                                             inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                               left  join cl_item_fornecedor  z on (y.sq_material         = z.sq_material and
                                                                                    'S'                   = z.pesquisa)
                                       where z.fim >= trunc(sysdate)
                                      group by y.sq_solicitacao_item
                                     )                        i on (a.sq_solicitacao_item  = i.sq_solicitacao_item)
                     where a.sq_siw_solicitacao = p_chave
                   )
             where qtd < 2;
            
            If w_cont = 0 Then
               select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                  from siw_tramite a
                 where a.sq_menu = p_menu
                   and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = w_tramite);
            End If;
         Elsif w_sg_tramite = 'CH' and w_menu.sigla = 'MTCONSUMO' Then
            -- Se o trâmite for de chefia imediata e os itens do pedido não exigirem autorização pelo chefe imediato, pula para o próximo.
            select count(*) into w_cont
              from siw_solicitacao              k
                   inner     join mt_saida      l on (k.sq_siw_solicitacao = l.sq_siw_solicitacao)
                     inner   join mt_saida_item m on (l.sq_mtsaida         = m.sq_mtsaida)
                       inner join mt_estoque    n on (m.sq_material        = n.sq_material)
             where n.chefe_autoriza     = 'S'
               and k.sq_siw_solicitacao = p_chave;
            
            If w_cont = 0 Then
               select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                  from siw_tramite a
                 where a.sq_menu = p_menu
                   and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = w_tramite);
            End If;
         Elsif w_solic.cadastrador = w_solic.solicitante and w_sg_tramite = 'CB' Then
            -- Se o trâmite for de ciência pelo beneficiário e o cadastrador for o beneficiário, pula para o próximo.
            select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
               from siw_tramite a
             where a.sq_menu = p_menu
               and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = w_tramite);
         Elsif w_menu.sigla = 'SRSOLCEL' Then
            If w_sg_tramite = 'PP' Then
               -- Se o trâmite for de pendência na entrega de acessórios de celular e não houver pendência, pula para o próximo.
               select count(*) into w_cont
                 from siw_solicitacao                       k
                      inner     join sr_solicitacao_celular l on (k.sq_siw_solicitacao = l.sq_siw_solicitacao)
                where l.pendencia          = 'S'
                  and k.sq_siw_solicitacao = p_chave;
               
               If w_cont = 0 Then
                  select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                     from siw_tramite a
                    where a.sq_menu = p_menu
                      and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = w_tramite);
               End If;
            Elsif w_sg_tramite = 'EE' Then
              -- Se o trâmite for de conclusão, atualiza o campo PENDENCIA.
              update sr_solicitacao_celular set pendencia = 'N', acessorios_pendentes = null where sq_siw_solicitacao = p_chave;
            End If;
         End If;
      Else
         -- Recupera dados do novo trâmite
         select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
            from siw_tramite a
           where a.sq_siw_tramite = p_novo_tramite;
           
         If w_sg_tramite = 'PP' and w_menu.sigla = 'SRSOLCEL' Then
            -- Se o trâmite for de pendência na entrega de acessórios de celular, atualiza o campo PENDENCIA.
            update sr_solicitacao_celular set pendencia = 'S', acessorios_pendentes = 'A ser inserido.' where sq_siw_solicitacao = p_chave;
         Elsif w_menu.sigla = 'MTCONSUMO' Then
            -- Atualiza o valor da solicitação
            update siw_solicitacao a 
               set a.valor     = 0,
                   a.conclusao = null
             where a.sq_siw_solicitacao = p_chave;
            
            -- Atualiza o saldo de estoque
            update mt_estoque_item a
               set saldo_atual = saldo_atual + (select sum(y.quantidade)
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
            -- Atualiza os protocolos vinculados a uma lista de exclusão
            update siw_solicitacao a
               set a.sq_siw_tramite = (select sq_siw_tramite from siw_tramite x where x.sq_menu = a.sq_menu and x.sigla = 'AT')
            where a.sq_siw_solicitacao in (select protocolo from pa_eliminacao where sq_siw_solicitacao = p_chave);
            
            update pa_eliminacao a set a.eliminacao = null where sq_siw_solicitacao = p_chave;
         Elsif w_menu.sigla = 'PAEMP' Then
            -- Atualiza os protocolos vinculados a uma lista de empréstimo
            update pa_emprestimo_item a set a.devolucao = null where sq_siw_solicitacao = p_chave;
         End If;
         
      End If;
   Else
      w_tramite := p_tramite;
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
        case p_tramite 
          when p_novo_tramite then 'Anotação: '||chr(13)||chr(10)||p_despacho
          else case p_devolucao 
                   when 'S' then 'Devolução da fase "' 
                            else 'Envio da fase "' 
               end ||a.nome||'" '||
               ' para a fase "'||b.nome||'".'||
               case p_devolucao
                   when 'S' then chr(13)||chr(10)||'Despacho: '||chr(13)||chr(10)||p_despacho
                   else coalesce(p_despacho,'')
               end
       end
       from siw_tramite a,
            siw_tramite b
      where a.sq_siw_tramite = p_tramite
        and b.sq_siw_tramite = w_tramite
   );
   Update siw_solicitacao set
      sq_siw_tramite        = w_tramite,
      conclusao             = null,
      executor              = case coalesce(w_sg_tramite,'--') when 'CI' then null else executor end,
      observacao            = case substr(w_menu.sigla,1,2) when 'FN' then observacao when 'PA' then observacao when 'CL' then observacao when 'MT' then observacao else null end,
      valor                 = case substr(w_menu.sigla,1,2) when 'FN' then valor when 'CL' then valor when 'SR' then valor else null end,
      opiniao               = null
   Where sq_siw_solicitacao = p_chave;

   -- Ajusta valores de pedidos de ARP
   If w_menu.sigla = 'CLRPCAD' and p_tramite <> nvl(p_novo_tramite, 0) Then
      select sigla into w_sg_tramite
        from siw_solicitacao        a
             inner join siw_tramite b on (a.sq_siw_tramite = b.sq_siw_tramite)
       where a.sq_siw_solicitacao = p_chave;

      If w_sg_tramite = 'CA' Then
         -- Pedido cancelado não tem valor
         update siw_solicitacao set 
             valor = 0
         where sq_siw_solicitacao = p_chave;
      Elsif w_sg_tramite = 'EE' Then
         -- Pedido autorizado leva em conta a quantidade autorizada
         update siw_solicitacao x set 
             valor = (select coalesce(sum(a.quantidade_autorizada*c.valor_unidade),0) as valor
                        from cl_solicitacao_item a 
                             inner   join cl_solicitacao_item_vinc b on (a.sq_solicitacao_item = b.item_pedido)
                               inner join cl_item_fornecedor       c on (b.item_licitacao      = c.sq_solicitacao_item) 
                       where sq_siw_solicitacao = x.sq_siw_solicitacao
                     )
         where x.sq_siw_solicitacao = p_chave;
      Else
         -- Caso contrário leva em conta a quantidade solicitada
         update siw_solicitacao x set 
             valor = (select coalesce(sum(a.quantidade*c.valor_unidade),0) as valor
                        from cl_solicitacao_item a 
                             inner   join cl_solicitacao_item_vinc b on (a.sq_solicitacao_item = b.item_pedido)
                               inner join cl_item_fornecedor       c on (b.item_licitacao      = c.sq_solicitacao_item) 
                       where sq_siw_solicitacao = x.sq_siw_solicitacao
                     )
         where x.sq_siw_solicitacao = p_chave;
      End If; 
   End If;
   
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
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo) values (w_chave, w_chave_arq);
   End If;
end SP_PutSolicEnvio;
/
