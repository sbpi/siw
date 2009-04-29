create or replace procedure SP_PutAcordoDadosAdicionais
   (p_operacao            in varchar2,
    p_chave               in number,
    p_numero_certame      in varchar2 default null,
    p_numero_ata          in varchar2 default null,
    p_tipo_reajuste       in number   default null,
    p_limite_variacao     in number   default null,
    p_indice_base         in varchar2 default null,
    p_sq_eoindicador      in number   default null,
    p_sq_lcfonte_recurso  in number   default null,
    p_espec_despesa       in number   default null,
    p_sq_lcmodalidade     in number    default null,    
    p_numero_empenho      in varchar2  default null,
    p_numero_processo     in varchar2  default null,
    p_assinatura          in date      default null,
    p_publicacao          in date      default null,
    p_financeiro_unico    in varchar2  default null,
    p_pagina_diario       in number    default null,
    p_condicao            in varchar2  default null,
    p_valor_caucao        in number   default null
   ) is
begin
   -- Atualiza o registro da demanda com os dados da conclusão.
   Update ac_acordo set
      numero_certame           = p_numero_certame,
      numero_ata               = p_numero_ata,
      tipo_reajuste            = p_tipo_reajuste,
      limite_variacao          = p_limite_variacao,
      indice_base              = p_indice_base,
      sq_eoindicador           = p_sq_eoindicador,
      sq_lcfonte_recurso       = p_sq_lcfonte_recurso,
      sq_especificacao_despesa = p_espec_despesa,
      sq_lcmodalidade          = p_sq_lcmodalidade,
      empenho                  = p_numero_empenho,
      assinatura               = p_assinatura,
      publicacao               = p_publicacao,
      financeiro_unico         = p_financeiro_unico,
      pagina_diario_oficial    = p_pagina_diario,
      condicoes_pagamento      = p_condicao,
      valor_caucao             = p_valor_caucao
   Where sq_siw_solicitacao = p_chave;
end SP_PutAcordoDadosAdicionais;
/
