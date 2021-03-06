create or replace FUNCTION sp_putCLParametro
   (p_cliente                   numeric,
    p_ano_corrente              numeric,
    p_dias_validade_pesquisa    numeric,
    p_dias_aviso_pesquisa       numeric,
    p_percentual_acrescimo      numeric,
    p_compra_central            varchar,
    p_pesquisa_central          varchar,
    p_contrato_central          varchar,
    p_banco_ata_central         varchar,    
    p_banco_preco_central       varchar,    
    p_codificacao_central       varchar,
    p_pede_valor_pedido         varchar,
    p_automatico                varchar,
    p_prefixo                   numeric,
    p_sequencial                numeric,
    p_sufixo                    numeric,
    p_cadastrador_geral         varchar
   ) RETURNS VOID AS $$
DECLARE
   
   p_operacao varchar(1);
   w_existe   numeric(18);
   
BEGIN
   -- Verifica se operação de inclusão ou alteração
   select count(*) into w_existe from cl_parametro a where a.cliente = p_cliente;
   If w_existe > 0 Then
      p_operacao := 'A';
   Else
      p_operacao := 'I';
   End If;
   
   -- Grava os parametros do módulo de viagens do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into cl_parametro
         (cliente, ano_corrente, dias_validade_pesquisa, dias_aviso_pesquisa, 
          percentual_acrescimo, compra_central, pesquisa_central, contrato_central, 
          banco_ata_central, banco_preco_central, codificacao_central, pede_valor_pedido,
          codificacao_automatica, prefixo, sequencial, sufixo, cadastrador_geral)
      values
         (p_cliente, p_ano_corrente, p_dias_validade_pesquisa, p_dias_aviso_pesquisa, 
          p_percentual_acrescimo, p_compra_central, p_pesquisa_central, p_contrato_central,
          p_banco_ata_central, p_banco_preco_central, p_codificacao_central, p_pede_valor_pedido,
          p_automatico, coalesce(p_prefixo,0), coalesce(p_sequencial,0), coalesce(p_sufixo,0), p_cadastrador_geral);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update cl_parametro
         set 
            ano_corrente            = p_ano_corrente,
            dias_validade_pesquisa  = p_dias_validade_pesquisa,
            dias_aviso_pesquisa     = p_dias_aviso_pesquisa,
            percentual_acrescimo    = p_percentual_acrescimo,
            compra_central          = p_compra_central,
            pesquisa_central        = p_pesquisa_central,
            contrato_central        = p_contrato_central,
            banco_ata_central       = p_banco_ata_central,
            banco_preco_central     = p_banco_preco_central,
            codificacao_central     = p_codificacao_central,
            pede_valor_pedido       = p_pede_valor_pedido,
            codificacao_automatica  = p_automatico, 
            prefixo                 = coalesce(p_prefixo,0), 
            sequencial              = coalesce(p_sequencial,0), 
            sufixo                  = coalesce(p_sufixo,0),
            cadastrador_geral       = p_cadastrador_geral
      where cliente = p_cliente;
      
      -- Atualiza as pesquisas de preço
      update cl_item_fornecedor a set fim = a.inicio + p_dias_validade_pesquisa where a.sq_solicitacao_item is null;
      
      -- Atualiza os dados dos materiais
      sp_ajustapesquisamaterial(p_cliente, null, 'TODOS');
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;