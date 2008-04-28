create or replace function siw.sp_putUnidade_CL
   (p_operacao             varchar,
    p_cliente              numeric,
    p_chave                numeric,
    p_unidade_pai          numeric,
    p_realiza_compra       varchar ,
    p_solicita_compra      varchar ,
    p_registra_pesquisa    varchar ,
    p_registra_contrato    varchar ,
    p_registra_judicial    varchar ,
    p_controla_banco_ata   varchar ,
    p_controla_banco_preco varchar ,
    p_codifica_item        varchar ,
    p_codificacao_restrita varchar ,
    p_padrao               varchar ,
    p_ativo                varchar 
   ) 
 RETURNS character varying AS
$BODY$declare

begin   
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw.cl_unidade 
         (sq_unidade,          cliente,            sq_unidade_pai,        realiza_compra,         solicita_compra,   registra_pesquisa,
          registra_contrato,   registra_judicial,  controla_banco_ata,    controla_banco_preco,   codifica_item,     codificacao_restrita,
          unidade_padrao,      ativo) 
      values 
         (p_chave,             p_cliente,           p_unidade_pai,        p_realiza_compra,       p_solicita_compra, p_registra_pesquisa,
          p_registra_contrato, p_registra_judicial, p_controla_banco_ata, p_controla_banco_preco, p_codifica_item,   p_codificacao_restrita,
          p_padrao,            p_ativo);
   Elsif p_operacao = 'A' Then
      update siw.cl_unidade set
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
      delete from siw.cl_unidade where sq_unidade = p_chave;
   End If;
end
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION  siw.sp_putUnidade_CL
   (p_operacao             varchar,
    p_cliente              numeric,
    p_chave                numeric,
    p_unidade_pai          numeric,
    p_realiza_compra       varchar ,
    p_solicita_compra      varchar ,
    p_registra_pesquisa    varchar ,
    p_registra_contrato    varchar ,
    p_registra_judicial    varchar ,
    p_controla_banco_ata   varchar ,
    p_controla_banco_preco varchar ,
    p_codifica_item        varchar ,
    p_codificacao_restrita varchar ,
    p_padrao               varchar ,
    p_ativo                varchar 
   )  OWNER TO siw;
