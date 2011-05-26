create or replace procedure SP_PutMtEntrada
   (p_operacao              in varchar2,
    p_cliente               in number,
    p_usuario               in number,
    p_chave                 in number   default null,
    p_copia                 in  number  default null,
    p_fornecedor            in number   default null,
    p_tipo_movimentacao     in number   default null,    
    p_situacao              in number   default null,
    p_solicitacao           in number   default null,
    p_documento             in number   default null,
    p_previsto              in date     default null,
    p_efetivo               in date     default null,
    p_tipo_doc              in number   default null,
    p_numero_doc            in varchar2 default null,
    p_data_doc              in date     default null,
    p_valor_doc             in number   default null,
    p_armazenamento         in date     default null,
    p_numero_empenho        in varchar2 default null,
    p_data_empenho          in date     default null,
    p_chave_nova            out number
   ) is
   w_chave        mt_entrada.sq_mtentrada%type             := p_chave;
   w_solicitacao  siw_solicitacao.sq_siw_solicitacao%type  := p_solicitacao;
   w_documento    fn_lancamento_doc.sq_lancamento_doc%type := p_documento;
   w_existe       number(4);
   w_arq          varchar2(4000) := ', ';
   
   w_cd_financ    varchar2(60) := null;

   cursor c_dados is
      select z.pessoa, w.sq_tipo_documento, w.numero, w.data, w.valor, w.sq_siw_solicitacao, w.sq_lancamento_doc
        from fn_lancamento_doc            w
             inner   join siw_solicitacao x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
               inner join siw_tramite     y on (x.sq_siw_tramite     = y.sq_siw_tramite and y.sigla <> 'CA')
             inner   join fn_lancamento   z on (w.sq_siw_solicitacao = z.sq_siw_solicitacao and z.cliente = p_cliente)
       where z.pessoa            = p_fornecedor
         and w.sq_tipo_documento = p_tipo_doc
         and w.numero            = p_numero_doc
         and w.data              = p_data_doc
         and w.valor             = p_valor_doc;

   cursor c_financeiro_geral is
      select distinct w.sq_pessoa as cliente, w.sq_menu, y.sq_unidade, 
             'ENTMAT-'||p_numero_doc||'-'||p_fornecedor as codigo_interno,
             'Lancamento financeiro histórico para vinculação a entrada de material.' as descricao, 
             null as processo, p_previsto as vencimento, 
             w1.sq_cidade_padrao as sq_cidade, null as sq_solic_pai, 
             w3.sq_forma_pagamento, p_tipo_doc as sq_tipo_documento,
             'Registro gerado automaticamente pelo sistema de materiais' as observacao, z.sq_tipo_lancamento, 
             p_previsto as inicio, p_efetivo as fim, x.sq_tipo_pessoa
        from siw_menu                           w
             inner   join siw_cliente           w1 on (w.sq_pessoa           = w1.sq_pessoa)
               inner join siw_tramite           w2 on (w.sq_menu             = w2.sq_menu and
                                                       w2.sigla              = 'AT'
                                                      )
               inner join co_forma_pagamento    w3 on (w1.sq_pessoa          = w3.cliente and w3.sigla = 'CREDITO'),
             co_pessoa                          x,
             sg_autenticacao                    y,
             (select sq_tipo_lancamento, nome as nm_lancamento
                from (select sq_tipo_lancamento, nome
                        from fn_tipo_lancamento k
                       where k.cliente                = p_cliente
                         and k.despesa                = 'S'
                         and k.sq_tipo_lancamento_pai is null
                         and k.ativo                  = 'S'
                      order by k.nome
                     ) k1
               where rownum = 1
             )                                  z
       where w.sq_pessoa          = p_cliente
         and w.sigla              = 'FNDEVENT'
         and x.sq_pessoa          = p_fornecedor
         and y.sq_pessoa          = p_usuario;

   cursor c_arquivos is
      select x.sq_siw_arquivo 
        from fn_documento_arq      x 
             inner join mt_entrada y on (x.sq_lancamento_doc = y.sq_lancamento_doc) 
       where y.sq_mtentrada = p_chave;


begin
   If p_solicitacao is null or p_documento is null or p_copia is not null Then
      -- Recupera a chave da solicitação e do documento
      w_existe := 0;
      for crec in c_dados loop
          w_solicitacao := crec.sq_siw_solicitacao;
          w_documento   := crec.sq_lancamento_doc;
          
          w_existe := 1;
          exit;
      end loop;
      
      If w_existe = 0 Then
          
          -- Cria o lançamento financeiro
          for crec in c_financeiro_geral loop
              If p_copia is null Then
                 w_cd_financ := crec.codigo_interno;
                  
                 sp_putfinanceirogeral(
                                 p_operacao           => 'I',
                                 p_cliente            => crec.cliente,
                                 p_chave              => null,
                                 p_menu               => crec.sq_menu,
                                 p_sq_unidade         => crec.sq_unidade,
                                 p_solicitante        => p_usuario,
                                 p_cadastrador        => p_usuario,
                                 p_descricao          => crec.descricao,
                                 p_vencimento         => crec.vencimento,
                                 p_valor              => p_valor_doc,
                                 p_data_hora          => 3,
                                 p_aviso              => 'S',
                                 p_dias               => '2',
                                 p_cidade             => crec.sq_cidade,
                                 p_projeto            => crec.sq_solic_pai,
                                 p_observacao         => crec.observacao,
                                 p_sq_tipo_lancamento => crec.sq_tipo_lancamento,
                                 p_sq_forma_pagamento => crec.sq_forma_pagamento,
                                 p_sq_tipo_pessoa     => crec.sq_tipo_pessoa,
                                 p_tipo_rubrica       => 5, -- despesas
                                 p_numero_processo    => crec.processo,
                                 p_per_ini            => crec.inicio,
                                 p_per_fim            => crec.fim,
                                 p_chave_nova         => w_solicitacao,
                                 p_codigo_interno     => w_cd_financ
                                );

              End If;
              
              -- Atualiza os dados do beneficiário
              update fn_lancamento set pessoa = p_fornecedor where sq_siw_solicitacao = w_solicitacao;

              -- Cria os documentos
              sp_putlancamentodoc(
                                 p_operacao           => 'I',
                                 p_chave              => w_solicitacao,
                                 p_chave_aux          => null,
                                 p_sq_tipo_documento  => crec.sq_tipo_documento,
                                 p_numero             => p_numero_doc,
                                 p_data               => p_previsto,
                                 p_serie              => null,
                                 p_valor              => p_valor_doc,
                                 p_patrimonio         => 'N',
                                 p_retencao           => 'N',
                                 p_tributo            => 'N',
                                 p_nota               => null,
                                 p_inicial            => 0,
                                 p_excedente          => 0,
                                 p_reajuste           => 0,
                                 p_chave_nova         => w_documento
                                );

          End Loop;
         
      End If;
   Elsif p_copia is null Then
      -- Atualiza os dados do documento
      update fn_lancamento_doc
         set sq_tipo_documento = p_tipo_doc,
             data              = p_data_doc,
             numero            = p_numero_doc,
             valor             = p_valor_doc
      where sq_lancamento_doc = p_documento;
   End If;
   
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_mtentrada.nextval into w_chave from dual;

      insert into mt_entrada
        (sq_mtentrada,      cliente,              fornecedor,          sq_tipo_movimentacao, sq_mtsituacao,    sq_siw_solicitacao, 
         sq_lancamento_doc, recebimento_previsto, recebimento_efetivo, armazenamento,        numero_empenho,   data_empenho
        )
      values
        (w_chave,           p_cliente,            p_fornecedor,        p_tipo_movimentacao,  p_situacao,       w_solicitacao,
         w_documento,       p_previsto,           p_efetivo,           p_armazenamento,      p_numero_empenho, p_data_empenho
        );
     
      If p_copia is not null Then
         -- copia os dados complementares da entrada de material selecionada
         insert into mt_entrada_item (
                 sq_entrada_item,         sq_mtentrada, sq_material, sq_almoxarifado, sq_mtsituacao, quantidade,     valor_unitario, 
                 fator_embalagem,         validade,     fabricacao,  vida_util,       lote_numero,   lote_bloqueado, sq_documento_item,
                 ordem,                   valor_total,  marca,       modelo)
         (select sq_entrada_item.nextval, w_chave,      sq_material, sq_almoxarifado, sq_mtsituacao, quantidade,     valor_unitario, 
                 fator_embalagem,         validade,     fabricacao,  vida_util,       lote_numero,   lote_bloqueado, sq_documento_item,
                 ordem,                   valor_total,  marca,       modelo
           from mt_entrada_item a
          where a.sq_mtentrada = p_copia
         );
      End If;
      
   Elsif p_operacao = 'A' Then -- Alteração
      update mt_entrada
         set fornecedor           = p_fornecedor,
             sq_tipo_movimentacao = p_tipo_movimentacao,
             sq_mtsituacao        = p_situacao,
             sq_siw_solicitacao   = w_solicitacao,
             sq_lancamento_doc    = w_documento,
             recebimento_previsto = p_previsto,
             recebimento_efetivo  = p_efetivo,
             armazenamento        = p_armazenamento,
             numero_empenho       = p_numero_empenho,
             data_empenho         = p_data_empenho
      where sq_mtentrada = p_chave;
       
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Monta string com a chave dos arquivos ligados à solicitação informada
      for crec in c_arquivos loop
         w_arq := w_arq || crec.sq_siw_arquivo;
      end loop;
      w_arq := substr(w_arq, 3, length(w_arq));

      delete fn_documento_arq where sq_lancamento_doc  = (select sq_lancamento_doc from mt_entrada where sq_mtentrada = p_chave);
      delete siw_arquivo      where sq_siw_arquivo    in (w_arq);

      delete mt_entrada_item where sq_mtentrada = p_chave;
      delete mt_entrada      where sq_mtentrada = p_chave;
      
   End If;
       
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;

end SP_PutMtEntrada;
/
