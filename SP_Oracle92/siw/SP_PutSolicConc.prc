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
       select b.sq_solicitacao_item, b.sq_material, max(c.valor_unidade) as maximo, min(c.valor_unidade) as minimo, avg(c.valor_unidade) as medio
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

   -- Grava os itens de uma licitação, indicando o vencedor
   for crec in c_vencedor loop
       update cl_item_fornecedor 
          set vencedor = 'S' 
       where sq_solicitacao_item = crec.sq_solicitacao_item
         and sq_material         = crec.sq_material
         and fornecedor          = crec.fornecedor;
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
end SP_PutSolicConc;
/
