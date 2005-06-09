create or replace procedure SP_PutAtualizaEtapa
   (p_chave               in number,
    p_chave_aux           in number,
    p_usuario             in number,
    p_perc_conclusao      in number,
    p_situacao_atual      in varchar2  default null,
    p_exequivel           in varchar2,
    p_justificativa_inex  in varchar2  default null,
    p_outras_medidas      in varchar2  default null
   ) is
begin
   -- Atualiza a tabela de etapas do projeto
   Update pj_projeto_etapa set
       perc_conclusao            = p_perc_conclusao,
       situacao_atual            = p_situacao_atual,
       sq_pessoa_atualizacao     = p_usuario,
       exequivel                 = p_exequivel,
       justificativa_inexequivel = p_justificativa_inex,
       outras_medidas            = p_outras_medidas,
       ultima_atualizacao    = sysdate
   where sq_siw_solicitacao = p_chave
     and sq_projeto_etapa   = p_chave_aux;
end SP_PutAtualizaEtapa;
/

