create or replace procedure sp_geraContrato
   (p_solicitacao in  number, 
    p_fornecedor  in  number,
    p_menu        in  number,
    p_usuario     in  number,
    p_codigo      out varchar2,
    p_chave       out number
   ) is
   w_cont       number(10) := 1;
   w_existe     number(4);
   w_menu       siw_menu%rowtype;

   cursor c_dados is
      select a.sq_siw_solicitacao, a.solicitante, a.cadastrador, a.executor, a.descricao, a.justificativa, a.inicio, a.fim, a.valor, a.sq_unidade, 
             a.sq_cc, a.palavra_chave, a.sq_cidade_origem, a.ano, a.observacao, a.codigo_interno, a.protocolo_siw,
             b.sq_especie_documento, b.sq_especificacao_despesa, b.sq_eoindicador, b.sq_lcfonte_recurso, b.sq_lcmodalidade, b.sq_lcjulgamento, 
             b.sq_lcsituacao, b.sq_unidade as unid_resp, b.numero_original, b.data_recebimento, b.processo, b.indice_base, b.tipo_reajuste, b.limite_variacao, 
             b.data_homologacao, b.data_diario_oficial, b.pagina_diario_oficial, b.financeiro_unico, b.decisao_judicial, b.numero_ata, 
             b.numero_certame, b.arp, b.prioridade, b.aviso_prox_conc, b.dias_aviso, b.interno, b.sq_financeiro, 
             b.nota_conclusao, b.data_abertura,
             c.sq_solicitacao_item, c.sq_material, c.quantidade, c.valor_unit_est, c.preco_menor, c.preco_maior, c.preco_medio, c.quantidade_autorizada, 
             c.cancelado, c.motivo_cancelamento, c.ordem, c.sq_unidade_medida, c.prazo_garantia, c.vistoria_previa, c.catalogo, 
             c.prazo_manutencao,
             e.sq_pessoa, e.sq_tipo_pessoa
        from siw_solicitacao                           a
             inner       join siw_menu                 a1 on (a.sq_menu             = a1.sq_menu)
             inner       join siw_tramite              a2 on (a.sq_siw_tramite      = a2.sq_siw_tramite)
             inner       join cl_solicitacao           b  on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
               inner     join cl_solicitacao_item      c  on (b.sq_siw_solicitacao  = c.sq_siw_solicitacao)
                 inner   join cl_item_fornecedor       d  on (c.sq_solicitacao_item = d.sq_solicitacao_item and
                                                              d.pesquisa            = 'N' and
                                                              d.vencedor            = 'S'
                                                             )
                   inner join co_pessoa                e  on (d.fornecedor          = e.sq_pessoa)
       where a.sq_siw_solicitacao = p_solicitacao
         and d.fornecedor         = p_fornecedor
      order by b.numero_certame, e.nome, lpad(c.ordem,4);
   
begin
  -- Verifica se a solicitação existe
  select count(*) into w_existe 
    from siw_solicitacao     a
         inner join siw_menu b on (a.sq_menu = b.sq_menu)
   where a.sq_siw_solicitacao = p_solicitacao;

  If w_existe = 0 Then 
     -- Se não for existir, retorna nulo
     p_codigo := null; 
     p_chave  := null;
     return;
  End If;

  -- Recupera os dados da opção de menu
  select * into w_menu from siw_menu where sq_menu = p_menu;
  
  for crec in c_dados loop
      If w_cont = 1 Then
         sp_putacordogeral(p_operacao           => 'I',
                           p_cliente            => w_menu.sq_pessoa,
                           p_chave              => null,
                           p_copia              => null,
                           p_menu               => w_menu.sq_menu,
                           p_unid_resp          => crec.unid_resp,
                           p_solicitante        => crec.solicitante,
                           p_cadastrador        => p_usuario,
                           p_sqcc               => crec.sq_cc,
                           p_descricao          => crec.descricao,
                           p_justificativa      => crec.justificativa,
                           p_inicio             => trunc(sysdate),
                           p_fim                => trunc(sysdate),
                           p_valor              => crec.valor,
                           p_data_hora          => w_menu.data_hora,
                           p_aviso              => 'S',
                           p_dias               => 45,
                           p_cidade             => crec.sq_cidade_origem,
                           p_projeto            => crec.sq_siw_solicitacao,
                           p_sq_tipo_acordo     => crec.sq_tipo_acordo,
                           p_objeto             => crec.objeto,
                           p_sq_tipo_pessoa     => crec.sq_tipo_pessoa,
                           p_sq_forma_pagamento => crec.sq_forma_pagamento,
                           p_forma_atual        => crec.forma_atual,
                           p_inicio_atual       => crec.inicio_atual,
                           p_etapa              => crec.etapa,
                           p_codigo             => crec.codigo,
                           p_titulo             => crec.titulo,
                           p_numero_empenho     => crec.numero_empenho,
                           p_numero_processo    => crec.numero_processo,
                           p_assinatura         => crec.assinatura,
                           p_publicacao         => crec.publicacao,
                           p_chave_nova         => p_chave,
                           p_codigo_interno     => p_codigo);
      End If;
  end loop;
  

end sp_geraContrato;
/
