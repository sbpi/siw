create or replace FUNCTION SP_PutCLARPItem
   (p_operacao                 varchar,
    p_cliente                  numeric,
    p_usuario                  numeric,
    p_solic                    numeric,
    p_item                     numeric,
    p_ordem                    varchar,
    p_codigo                   varchar,
    p_fabricante               varchar,
    p_marca_modelo             varchar,
    p_embalagem                varchar,
    p_fator                    numeric,
    p_quantidade               numeric,
    p_valor                    numeric,
    p_cancelado                varchar,
    p_motivo                   varchar,
    p_origem                   numeric   
   ) RETURNS VOID AS $$
DECLARE
   w_item_solic    numeric(18)  := p_item;
   w_valor         numeric(18,4);
   w_material      numeric(18);
   w_valor         float;
   w_menu          siw_menu%rowtype;
   w_acordo        ac_acordo%rowtype;
   w_item_sol      cl_solicitacao_item%rowtype;
   w_item_forn     cl_item_fornecedor%rowtype;
   w_sg_tramite    siw_tramite.sigla%type;
   w_chave_dem     numeric(18) := null;
   w_log           varchar(4000) := '';
BEGIN
   -- recupera os dados do serviço
   select b.* into w_menu
     from siw_solicitacao      a
          inner join siw_menu  b on (a.sq_menu = b.sq_menu)
     where a.sq_siw_solicitacao = p_solic;
     
   If p_operacao in ('I','A') Then
      -- recupera a chave do material
      select sq_material into w_material from cl_material where cliente = p_cliente and codigo_interno = p_codigo;

      -- recupera os dados do serviço
      select a.*
        into w_acordo
        from ac_acordo a
       where a.sq_siw_solicitacao = p_solic;
      
      -- Recupera o trâmite atual do acordo
      select sigla into w_sg_tramite
        from siw_solicitacao        a
             inner join siw_tramite b on (a.sq_siw_tramite = b.sq_siw_tramite)
       where a.sq_siw_solicitacao = p_solic;
   End If;
   
   If p_operacao = 'I' Then
      select sq_solicitacao_item.nextval into w_item_solic from dual;
      -- Insere registro em 
      insert into cl_solicitacao_item
        (sq_solicitacao_item, sq_siw_solicitacao, ordem,       sq_material,           quantidade,   cancelado,   motivo_cancelamento,
         valor_unit_est,      sq_unidade_medida,  quantidade_autorizada,              dias_validade_proposta,
         preco_menor,                             preco_maior,                        preco_medio
        )
      (select 
         w_item_solic,        p_solic,            p_ordem,     w_material,            p_quantidade, p_cancelado, p_motivo,
         p_valor,             a.sq_unidade_medida,p_quantidade,                       (w_acordo.fim - w_acordo.inicio),
         coalesce(a.pesquisa_preco_menor,p_valor),
         coalesce(a.pesquisa_preco_maior,p_valor),
         coalesce(a.pesquisa_preco_medio,p_valor)
       from cl_material a
       where sq_material = w_material
      );
      
      -- Insere registro na tabela CL_ITEM_FORNECEDOR
      insert into cl_item_fornecedor
        (sq_item_fornecedor,         sq_solicitacao_item,    sq_material, fornecedor,           inicio,          fim,
         valor_unidade,              valor_item,             ordem,       vencedor,             pesquisa,        fabricante, 
         marca_modelo,               embalagem,              dias_validade_proposta,            origem,          fator_embalagem)
      values
        (sq_item_fornecedor.nextval, w_item_solic,           w_material,  w_acordo.outra_parte, w_acordo.inicio, w_acordo.fim,
         p_valor,                    (p_valor*p_quantidade), p_ordem,     'S',                  'N',             p_fabricante, 
         p_marca_modelo,             p_embalagem,            (w_acordo.fim - w_acordo.inicio),  'PF',            p_fator
        );

      If p_origem is not null Then
         -- Se o item foi herdado, grava o vínculo
         insert into cl_solicitacao_item_vinc (item_licitacao, item_pedido) values (p_origem, w_item_solic);
      End If;
   Elsif p_operacao = 'A' Then
      
      -- Se for alteração de item na fase de execução, registra o log
      If w_sg_tramite <> 'CI' Then
         -- Recupera os dados atuais do item da solicitação
         select * into w_item_sol from cl_solicitacao_item where sq_solicitacao_item = w_item_solic;
         
         -- Recupera os dados atuais do item do fornecedor
         select * into w_item_forn from cl_item_fornecedor where sq_solicitacao_item = w_item_solic;
         
         -- Verifica se houve alteração nos dados do item
         If w_item_forn.fabricante <> p_fabricante            Then w_log := w_log || chr(13)||chr(10)||'Fabricante: de "'||w_item_forn.fabricante||'" para "'||p_fabricante||'"'; End If;
         If w_item_forn.marca_modelo <> p_marca_modelo        Then w_log := w_log || chr(13)||chr(10)||'Marca/modelo: de "'||w_item_forn.marca_modelo||'" para "'||p_marca_modelo||'"'; End If;
         If w_item_forn.embalagem <> p_embalagem              Then w_log := w_log || chr(13)||chr(10)||'Embalagem: de "'||w_item_forn.embalagem||'" para "'||p_embalagem||'"'; End If;
         If w_item_forn.fator_embalagem <> p_fator            Then w_log := w_log || chr(13)||chr(10)||'Fator de embalagem: de "'||w_item_forn.fator_embalagem||'" para "'||p_fator||'"'; End If;
         If w_item_forn.valor_unidade <> p_valor              Then w_log := w_log || chr(13)||chr(10)||'Valor unitário: de "'||fValor(w_item_forn.valor_unidade,'T',4)||'" para "'||fValor(p_valor,'T',4)||'"'; End If;
         If w_item_sol.quantidade_autorizada <> p_quantidade  Then w_log := w_log || chr(13)||chr(10)||case p_cliente when 9614 then 'CMM' else 'Quantidade' end||': de "'||fValor(w_item_sol.quantidade_autorizada,'T',2)||'" para "'||fValor(p_quantidade,'T',2)||'"'; End If;
         If w_item_sol.cancelado <> p_cancelado               Then 
            w_log := w_log || chr(13)||chr(10)||'Indisponível: de "'||case w_item_sol.cancelado when  'S' then 'Sim' else 'Não' end ||'" para "'||case p_cancelado when  'S' then 'Sim' else 'Não' end ||'"'; 
         End If;
         If coalesce(w_item_sol.motivo_cancelamento,'-') <> coalesce(p_motivo,'-') Then 
            w_log := w_log || chr(13)||chr(10)||'Motivo: de "'||coalesce(w_item_sol.motivo_cancelamento,'')||'" para "'||coalesce(p_motivo,'')||'"'; 
         End If;
         
         If length(w_log) > 0 Then
            -- Recupera a nova chave da tabela de encaminhamentos da demanda
            select sq_acordo_log.nextval into w_chave_dem from dual;
         
            -- Insere registro na tabela de encaminhamentos da demanda
            Insert into ac_acordo_log 
               (sq_acordo_log,             sq_siw_solicitacao, cadastrador, 
                destinatario,              data_inclusao,      observacao, 
                despacho,                  sq_siw_solic_log
               )
            Values (
                w_chave_dem,               p_solic,            p_usuario,
                null,                      now(),            'Alteração do item '||p_ordem,
                'Dados alterados: '||w_log,null
             );
          End If;
      End If;

      -- Altera registro
      update cl_solicitacao_item 
         set ordem                  = p_ordem,
             sq_material            = w_material,
             quantidade             = p_quantidade,
             cancelado              = p_cancelado,
             motivo_cancelamento    = p_motivo,
             valor_unit_est         = p_valor,
             preco_menor            = p_valor,
             preco_maior            = p_valor,
             preco_medio            = p_valor,
             quantidade_autorizada  = p_quantidade,
             dias_validade_proposta = (w_acordo.fim - w_acordo.inicio)
      where sq_solicitacao_item = w_item_solic;
      
      -- Insere registro na tabela CL_ITEM_FORNECEDOR
      update cl_item_fornecedor 
         set ordem                  = p_ordem,
             sq_material            = w_material,
             fornecedor             = w_acordo.outra_parte,
             inicio                 = w_acordo.inicio,
             fim                    = w_acordo.fim,
             valor_unidade          = p_valor,
             valor_item             = (p_valor*p_quantidade),
             fabricante             = p_fabricante,
             marca_modelo           = p_marca_modelo,
             embalagem              = p_embalagem,
             fator_embalagem        = p_fator,
             dias_validade_proposta = (w_acordo.fim - w_acordo.inicio)
      where sq_solicitacao_item = w_item_solic;
   Elsif p_operacao = 'E' Then
      DELETE FROM cl_item_fornecedor  where sq_solicitacao_item = w_item_solic;
      DELETE FROM cl_solicitacao_item where sq_solicitacao_item = w_item_solic;
   End If;
   
   -- Atualiza o valor da solicitação
   update siw_solicitacao 
      set valor = coalesce((select sum(valor_unit_est*quantidade) from cl_solicitacao_item where sq_siw_solicitacao = p_solic),0)
   where sq_siw_solicitacao = p_solic;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;