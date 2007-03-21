create or replace procedure SP_PutAcordoDadosAdicionais
   (p_operacao            in varchar2,
    p_chave               in number,
    p_numero_certame      in varchar2 default null,
    p_numero_ata          in varchar2 default null,
    p_tipo_reajuste       in number   default null,
    p_limite_variacao     in number   default null,
    p_indice_base         in varchar2 default null,
    p_sq_eoindicador      in number   default null,
    p_classif_orc         in varchar2 default null,
    p_espec_despesa       in varchar2 default null,
    p_sq_lcfonte_recurso  in number   default null
   ) is
begin
   -- Atualiza o registro da demanda com os dados da conclusão.
   Update ac_acordo set
      numero_certame          = p_numero_certame,
      numero_ata              = p_numero_ata,
      tipo_reajuste           = p_tipo_reajuste,
      limite_variacao         = p_limite_variacao,
      indice_base             = p_indice_base,
      sq_eoindicador          = p_sq_eoindicador,
      classificacao_orcamento = p_classif_orc,
      especificacao_despesa   = p_espec_despesa
   Where sq_siw_solicitacao = p_chave;
end SP_PutAcordoDadosAdicionais;
/
