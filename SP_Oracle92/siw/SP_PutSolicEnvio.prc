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

         -- Decide a tramitação em função do valor do pedido
         If w_menu.sq_pessoa = 10135 and substr(w_menu.sigla,1,4)='CLPC' Then
            -- Regra da ABDI: 
            --   após chefia imediata, pedidos de compra até 5mil vão para GERAF, acima de 15mil vão para DIREX, outros vão para presidente ou diretor
            --   após trâmite intermediario, vai para GERAF concluir.
            If w_or_tramite in (3,4) Then
               If w_solic.valor > 15000 and w_or_tramite = 4 Then
                  select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                     from siw_tramite a
                    where a.sq_menu = p_menu
                      and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = w_tramite);
               Elsif w_solic.valor <= 5000 Then
                  select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                     from siw_tramite a
                    where a.sq_menu = p_menu
                      and a.sigla   = 'AF';
               End If;
            Elsif w_or_tramite = 5 Then
               select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                  from siw_tramite a
                 where a.sq_menu = p_menu
                   and a.sigla   = 'AF';
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
         End If;
      Else
         select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
            from siw_tramite a
           where a.sq_siw_tramite = p_novo_tramite;
           
         If w_menu.sigla = 'PAELIM' Then
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
                   else ''
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
      observacao            = case substr(w_menu.sigla,1,2) when 'FN' then observacao when 'PA' then observacao when 'CL' then observacao else null end,
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

   commit;
      
end SP_PutSolicEnvio;
/
