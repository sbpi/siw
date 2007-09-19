create or replace procedure SP_PutCLDadosAnalise
   (p_operacao            in varchar2,
    p_chave               in number,
    p_sq_lcmodalidade     in number   default null,    
    p_numero_processo     in varchar2 default null,
    p_numero_certame      in varchar2 default null,
    p_tipo_reajuste       in number   default null,
    p_indice_base         in varchar2 default null,
    p_sq_eoindicador      in number   default null,
    p_limite_variacao     in number   default null,
    p_sq_lcfonte_recurso  in number   default null,
    p_sq_espec_despesa    in number   default null,
    p_sq_lcjulgamento     in number   default null,
    p_financeiro_unico    in varchar2 default null
   ) is
begin
   -- Atualiza a tabela da licitação com os dados da análise
   Update cl_solicitacao set
      sq_lcmodalidade          = p_sq_lcmodalidade,
      processo                 = p_numero_processo,
      numero_certame           = p_numero_certame,
      tipo_reajuste            = p_tipo_reajuste,
      indice_base              = p_indice_base,
      sq_eoindicador           = p_sq_eoindicador,
      limite_variacao          = p_limite_variacao,
      sq_lcfonte_recurso       = p_sq_lcfonte_recurso,
      sq_especificacao_despesa = p_sq_espec_despesa,
      sq_lcjulgamento          = p_sq_lcjulgamento,
      financeiro_unico         = p_financeiro_unico
   Where sq_siw_solicitacao = p_chave;
end SP_PutCLDadosAnalise;
/
