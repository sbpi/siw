create or replace procedure sp_putMtConsumoGeral
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_copia               in  number   default null,
    p_menu                in number,
    p_unidade             in number    default null,
    p_solicitante         in number    default null,
    p_cadastrador         in number    default null,
    p_justificativa       in varchar2  default null,
    p_observacao          in varchar2  default null,
    p_fim                 in date      default null,
    p_codigo              in varchar2  default null,
    p_observacao_log      in varchar2  default null,
    p_chave_nova          out number
   ) is

   w_data         date;
   w_arq          varchar2(4000) := ', ';
   w_chave        siw_solicitacao.sq_siw_solicitacao%type;
   w_chave_saida  mt_saida.sq_mtsaida%type;
   w_log_sol      number(18);
   w_codigo       varchar(60);
   w_menu         siw_menu%rowtype;
   w_almoxarifado mt_almoxarifado.sq_almoxarifado%type;
   w_movimentacao mt_tipo_movimentacao.sq_tipo_movimentacao%type;
   w_unidade      eo_unidade.sq_unidade%type;

   cursor c_arquivos is
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
begin
   -- Recupera a hora atual
   w_data := sysdate;
   
   If p_menu is not null and p_unidade is not null Then
      -- Recupera o almoxarifado padrão
      select sq_almoxarifado into w_almoxarifado
        from (select 1 as ordem, a.sq_almoxarifado, a.nome
                from mt_almoxarifado                     a
                     inner       join siw_menu           b on (a.cliente            = b.sq_pessoa and b.sq_menu = p_menu)
                     inner       join eo_localizacao     c on (a.sq_localizacao     = c.sq_localizacao)
                       inner     join eo_unidade         d on (c.sq_unidade         = d.sq_unidade)
                         inner   join co_pessoa_endereco e on (d.sq_pessoa_endereco = e.sq_pessoa_endereco)
                           inner join eo_unidade         f on (e.sq_pessoa_endereco = f.sq_pessoa_endereco and
                                                               f.sq_unidade         = p_unidade
                                                              )
               where a.ativo = 'S'
              UNION
              select 2 as ordem, a.sq_almoxarifado, a.nome
                from mt_almoxarifado                     a
                     inner       join siw_menu           b on (a.cliente            = b.sq_pessoa and b.sq_menu = p_menu)
                       inner     join eo_unidade         d on (b.sq_unid_executora  = d.sq_unidade)
                         inner   join eo_localizacao     c on (d.sq_pessoa_endereco = c.sq_pessoa_endereco and
                                                               a.sq_localizacao     = c.sq_localizacao
                                                              )
               where a.ativo = 'S'
              order by ordem, nome
             )
       where rownum = 1;

      -- Recupera a unidade vinculada ao almoxarifado padrão
      select a.sq_unidade into w_unidade
        from eo_localizacao             a
             inner join mt_almoxarifado b on (a.sq_localizacao = b.sq_localizacao)
       where b.sq_almoxarifado = w_almoxarifado;
   End If;

   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave da tabela de solicitações e de saídas de material
      select sq_siw_solicitacao.nextval into w_chave       from dual;
      select sq_mtsaida.nextval         into w_chave_saida from dual;
      
      -- Recupera o tipo de movimentação
      select sq_tipo_movimentacao into w_movimentacao
        from (select a.sq_tipo_movimentacao
                from mt_tipo_movimentacao a
                     inner join siw_menu  b on (a.cliente = b.sq_pessoa and b.sq_menu = p_menu)
               where a.ativo        = 'S'
                 and a.entrada      = 'N'
                 and a.saida        = 'S'
                 and a.consumo      = 'S'
                 and a.permanente   = 'N'
                 and a.orcamentario = 'S'
              order by a.nome
             )
       where rownum = 1;

      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,        sq_siw_tramite,      solicitante,      cadastrador,
         justificativa,      fim,            inclusao,            ultima_alteracao, sq_unidade,
         sq_cidade_origem,   codigo_interno, observacao,          valor)
      (select
         w_Chave,            p_menu,          a.sq_siw_tramite,   p_solicitante,    p_cadastrador,
         p_justificativa,    p_fim,           w_data,             w_data,           p_unidade,
         c.sq_cidade,        p_codigo,        p_observacao,       0
         from siw_tramite                   a,
              eo_unidade                    b
              inner join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
        where a.sq_menu    = p_menu
          and a.sigla      = 'CI'
          and b.sq_unidade = p_unidade
      );

      -- Insere registro em MT_SAIDA
      insert into mt_saida
        (sq_mtsaida,    sq_almoxarifado, sq_tipo_movimentacao, sq_siw_solicitacao, sq_unidade_origem, sq_unidade_destino, sq_pessoa_destino)
      values
        (w_chave_saida, w_almoxarifado,  w_movimentacao,       w_chave,            w_unidade,         p_unidade,          null);

      If p_codigo is null Then
         geracodigointerno(w_chave,null,w_codigo);
         update siw_solicitacao set
                codigo_interno = w_codigo
          where sq_siw_solicitacao = w_chave;
      End If;
      -- Insere log da solicitação
      Insert Into siw_solic_log
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa,
          sq_siw_tramite,            data,               devolucao,
          observacao
         )
      (select
          sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          w_data,             'N',
          'Cadastramento inicial'
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );

      -- Se a solicitacao foi copiado de outra, grava os dados complementares
      If p_copia is not null Then
         -- Copia os itens da solicitação
         insert into mt_saida_item
                (sq_saida_item,         sq_mtsaida,          sq_material,    sq_permanente,   
                 quantidade_pedida,     quantidade_entregue, valor_unitario, data_efetivacao)
         (select sq_saida_item.nextval, w_chave_saida,       x.sq_material,  x.sq_permanente, 
                 x.quantidade_pedida,   0,                   0,              null
            from mt_saida                 w
                 inner join mt_saida_item x on (w.sq_mtsaida = x.sq_mtsaida)
           where w.sq_siw_solicitacao = p_copia
         );
      End If;
   Elsif p_operacao = 'T' Then -- Atendimento
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          ultima_alteracao = w_data
      where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_unidade       = p_unidade,
          solicitante      = p_solicitante,
          justificativa    = p_justificativa,
          observacao       = p_observacao,
          fim              = p_fim,
          ultima_alteracao = w_data
      where sq_siw_solicitacao = p_chave;

      If p_codigo is not null Then
         update siw_solicitacao set codigo_interno = p_codigo where sq_siw_solicitacao = p_chave;
      End If;

      update mt_saida
         set sq_almoxarifado      = w_almoxarifado,
             sq_unidade_origem    = w_unidade,
             sq_unidade_destino   = p_unidade,
             sq_pessoa_destino    = null
       where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Recupera os dados do menu
      select * into w_menu from siw_menu where sq_menu = p_menu;
      
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;

      -- Se não tem atividades vinculadas nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If w_log_sol > 1 or w_menu.cancela_sem_tramite = 'S' Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa,
             sq_siw_tramite,            data,                 devolucao,
             observacao
            )
         (select
             sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          w_data,               'N',
             coalesce(p_observacao_log,'Cancelamento')
            from siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
         );

         -- Recupera a chave que indica que a solicitação está cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';

         -- Atualiza a situação da solicitação
         update siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = p_chave;
      Else
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));

         -- Remove os registros vinculados a solicitacao
         delete siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         delete siw_arquivo       where sq_siw_arquivo     in (w_arq);

         -- Remove os itens da saída
         delete mt_saida_item     where sq_mtsaida = (select sq_mtsaida from mt_saida where sq_siw_solicitacao = p_chave);

         -- Remove o registro na tabela de saídas
         delete mt_saida          where sq_siw_solicitacao = p_chave;

         -- Remove o log da solicitação
         delete siw_solic_log     where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao   where sq_siw_solicitacao = p_chave;
      End If;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end sp_putMtConsumoGeral;
/
