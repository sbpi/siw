create or replace procedure sp_putCLParametro
   (p_cliente                  in  number,
    p_ano_corrente             in  number,
    p_dias_validade_pesquisa   in  number,
    p_dias_aviso_pesquisa      in  number,
    p_percentual_acrescimo     in  number,
    p_compra_central           in  varchar2,
    p_pesquisa_central         in  varchar2,
    p_contrato_central         in  varchar2,
    p_banco_ata_central        in  varchar2,    
    p_banco_preco_central      in  varchar2,    
    p_codificacao_central      in  varchar2    
   ) is
   
   p_operacao varchar2(1);
   w_existe   number(18);
   
begin
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
          banco_ata_central, banco_preco_central, codificacao_central)
      values
         (p_cliente, p_ano_corrente, p_dias_validade_pesquisa, p_dias_aviso_pesquisa, 
          p_percentual_acrescimo, p_compra_central, p_pesquisa_central, p_contrato_central,
          p_banco_ata_central, p_banco_preco_central, p_codificacao_central);
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
            codificacao_central     = p_codificacao_central
       where cliente = p_cliente;
   End If;
end SP_PutCLParametro;
/
