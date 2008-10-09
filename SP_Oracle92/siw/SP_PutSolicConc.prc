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
    p_nome_original       in varchar2  default null
   ) is
   w_chave_dem     number(18) := null;
   w_chave_arq     number(18) := null;
   w_menu          siw_menu%rowtype;
   w_menu_lic      siw_menu%rowtype;
   w_solic         siw_solicitacao%rowtype;
   w_pedido        cl_solicitacao%rowtype;
   w_chave_nova    siw_solicitacao.sq_siw_solicitacao%type;
   w_codigo        siw_solicitacao.codigo_interno%type;
   
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
begin
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
       'Conclusão da solicitação');
       
   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      conclusao      = coalesce(to_date(p_fim,'dd/mm/yyyy, hh24:mi'),sysdate),
      executor       = p_executor,
      valor          = coalesce(p_valor,valor),
      observacao     = p_nota_conclusao,
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu=p_menu 
                           and Nvl(sigla,'z')='AT'
                       )
   Where sq_siw_solicitacao = p_chave;

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

   -- Recupera os dados do serviço
   select * into w_menu from siw_menu where sq_menu = p_menu;

   -- Se ABDI e Pedido de compra, gera licitação na fase de cotação de preços
   If w_menu.sq_pessoa = 10135 and substr(w_menu.sigla,1,4)='CLPC' Then

      -- Recupera os dados do serviço de licitação
      select * into w_menu_lic from siw_menu where sq_pessoa = w_menu.sq_pessoa and tramite = 'S' and substr(sigla,1,4)='CLLC';

      -- Recupera os dados da solicitação
      select * into w_solic from siw_solicitacao where sq_siw_solicitacao = p_chave;
      
      -- Recupera os dados do pedido
      select * into w_pedido from cl_solicitacao where sq_siw_solicitacao = p_chave;
      
      -- Gera a solicitação
      sp_putclgeral(p_operacao          => 'I',
                    p_chave             => null,
                    p_copia             => null,
                    p_menu              => w_menu_lic.sq_menu,
                    p_unidade           => w_solic.sq_unidade,
                    p_solicitante       => w_solic.solicitante,
                    p_cadastrador       => w_solic.cadastrador,
                    p_executor          => null,
                    p_plano             => w_solic.sq_plano,
                    p_objetivo          => null,
                    p_sqcc              => w_solic.sq_cc,
                    p_solic_pai         => w_solic.sq_solic_pai,
                    p_justificativa     => w_solic.justificativa,
                    p_observacao        => w_solic.observacao,
                    p_inicio            => w_solic.inicio,
                    p_fim               => w_solic.fim,
                    p_valor             => w_solic.valor,
                    p_codigo            => null,
                    p_prioridade        => w_pedido.prioridade,
                    p_aviso             => w_pedido.aviso_prox_conc,
                    p_dias              => w_pedido.dias_aviso,
                    p_cidade            => w_solic.sq_cidade_origem,
                    p_decisao_judicial  => w_pedido.decisao_judicial,
                    p_numero_original   => w_pedido.numero_original,
                    p_data_recebimento  => w_pedido.data_recebimento,
                    p_arp               => w_pedido.arp,
                    p_interno           => w_pedido.interno,
                    p_especie_documento => w_pedido.sq_especie_documento,
                    p_chave_nova        => w_chave_nova);
     
      -- Recupera os dados da licitação criada
      select * into w_solic from siw_solicitacao where sq_siw_solicitacao = w_chave_nova;

      -- Grava os itens da licitação
      for crec in c_itens loop
          sp_putclsolicitem(p_operacao            => 'V',
                            p_chave_aux           => null,
                            p_chave               => w_chave_nova,
                            p_chave_aux2          => crec.sq_solicitacao_item,
                            p_material            => null,
                            p_quantidade          => null,
                            p_qtd_ant             => null,
                            p_valor               => null,
                            p_cancelado           => null,
                            p_motivo_cancelamento => null);
      end loop;

      -- Gera a solicitação
      sp_putsolicenvio(p_menu          => w_menu_lic.sq_menu,
                       p_chave         => w_chave_nova,
                       p_pessoa        => p_pessoa,
                       p_tramite       => w_solic.sq_siw_tramite,
                       p_novo_tramite  => null,
                       p_devolucao     => 'N',
                       p_despacho      => 'Envio automático de licitação.',
                       p_caminho       => null,
                       p_tamanho       => null,
                       p_tipo          => null,
                       p_nome_original => null);
   End If;

end SP_PutSolicConc;
/
