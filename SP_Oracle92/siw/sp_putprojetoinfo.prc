create or replace procedure SP_PutProjetoInfo
   (p_chave                    in  number,
    p_descricao                in  varchar2 default null,
    p_justificativa            in  varchar2 default null,    
    p_problema                 in  varchar2 default null,
    p_ds_acao                  in  varchar2 default null,
    p_publico_alvo             in  varchar2 default null,
    p_estrategia               in  varchar2 default null,
    p_indicadores              in  varchar2 default null,
    p_objetivo                 in  varchar2 default null
   ) is
begin
   -- Altera os registro
      update siw_solicitacao set
        descricao = trim(p_descricao),
        justificativa = trim(p_justificativa)
        where sq_siw_solicitacao = p_chave;
      
      update or_acao set
        problema     = trim(p_problema),
        descricao    = trim(p_ds_acao),
        publico_alvo = trim(p_publico_alvo),
        estrategia   = trim(p_estrategia),
        indicadores  = trim(p_indicadores),
        objetivo     = trim(p_objetivo)
        where sq_siw_solicitacao = p_chave;
   
end SP_PutProjetoInfo;
/

