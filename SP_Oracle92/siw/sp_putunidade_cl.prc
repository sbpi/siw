create or replace procedure sp_putUnidade_CL
   (p_operacao             in  varchar2,
    p_cliente              in  number   default null,
    p_chave                in  number   default null,
    p_unidade_pai          in  number   default null,
    p_realiza_compra       in  varchar2 default null,
    p_solicita_compra      in  varchar2 default null,
    p_registra_pesquisa    in  varchar2 default null,
    p_registra_contrato    in  varchar2 default null,
    p_registra_judicial    in  varchar2 default null,
    p_controla_banco_ata   in  varchar2 default null,
    p_controla_banco_preco in  varchar2 default null,
    p_codifica_item        in  varchar2 default null,
    p_codificacao_restrita in  varchar2 default null,
    p_padrao               in  varchar2 default null,
    p_ativo                in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into cl_unidade
         (sq_unidade,          cliente,            sq_unidade_pai,        realiza_compra,         solicita_compra,   registra_pesquisa,
          registra_contrato,   registra_judicial,  controla_banco_ata,    controla_banco_preco,   codifica_item,     codificacao_restrita,
          unidade_padrao,      ativo)
      values
         (p_chave,             p_cliente,           p_unidade_pai,        p_realiza_compra,       p_solicita_compra, p_registra_pesquisa,
          p_registra_contrato, p_registra_judicial, p_controla_banco_ata, p_controla_banco_preco, p_codifica_item,   p_codificacao_restrita,
          p_padrao,            p_ativo);
   Elsif p_operacao = 'A' Then
      update cl_unidade set
         sq_unidade_pai       = p_unidade_pai,
         realiza_compra       = p_realiza_compra,
         solicita_compra      = p_solicita_compra,
         registra_pesquisa    = p_registra_pesquisa,
         registra_contrato    = p_registra_contrato,
         registra_judicial    = p_registra_judicial,
         controla_banco_ata   = p_controla_banco_ata,
         controla_banco_preco = p_controla_banco_preco,
         codifica_item        = p_codifica_item,
         codificacao_restrita = p_codificacao_restrita,
         unidade_padrao       = p_padrao,
         ativo                = p_ativo
       where sq_unidade = p_chave;

   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete cl_unidade where sq_unidade = p_chave;
   End If;
end sp_putUnidade_CL;
/
