create or replace procedure SP_PutCLARPItem
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_usuario                  in number    default null,
    p_solic                    in  number,
    p_item                     in  number   default null,
    p_ordem                    in  varchar2 default null,
    p_codigo                   in  varchar2 default null,
    p_fabricante               in  varchar2 default null,
    p_marca_modelo             in  varchar2 default null,
    p_embalagem                in  varchar2 default null,
    p_fator                    in  number   default null,
    p_quantidade               in  number   default null,
    p_valor                    in  number   default null,
    p_cancelado                in  varchar2 default null,
    p_motivo                   in  varchar2 default null
   ) is
   w_item_solic    number(18)  := p_item;
   w_valor         number(18,4);
   w_material      number(18);
   w_valor         float;
   w_menu          siw_menu%rowtype;
   w_acordo        ac_acordo%rowtype;
   w_item_sol      cl_solicitacao_item%rowtype;
   w_item_forn     cl_item_fornecedor%rowtype;
   w_sg_tramite    siw_tramite.sigla%type;
   w_chave_dem     number(18) := null;
   w_log           varchar2(4000) := '';
begin
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
         valor_unit_est,      preco_menor,        preco_maior, preco_medio,           quantidade_autorizada,     dias_validade_proposta
        )
      values (
         w_item_solic,        p_solic,            p_ordem,     w_material,            p_quantidade, p_cancelado, p_motivo,
         p_valor,             p_valor,            p_valor,     p_valor,               p_quantidade,              (w_acordo.fim - w_acordo.inicio)
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
         If w_item_sol.quantidade_autorizada <> p_quantidade  Then w_log := w_log || chr(13)||chr(10)||'CMM: de "'||fValor(w_item_sol.quantidade_autorizada,'T',2)||'" para "'||fValor(p_quantidade,'T',2)||'"'; End If;
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
                null,                      sysdate,            'Alteração do item '||p_ordem,
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
      delete cl_item_fornecedor  where sq_solicitacao_item = w_item_solic;
      delete cl_solicitacao_item where sq_solicitacao_item = w_item_solic;
   End If;
   
   -- Atualiza o valor da solicitação
   update siw_solicitacao 
      set valor = coalesce((select sum(valor_unit_est*quantidade) from cl_solicitacao_item where sq_siw_solicitacao = p_solic),0)
   where sq_siw_solicitacao = p_solic;
end SP_PutCLARPItem;
/
