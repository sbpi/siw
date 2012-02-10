create or replace procedure SP_PutSolicOpiniao
   (p_chave               in number,
    p_opiniao             in number,
    p_motivo              in varchar2
   ) is
begin
   -- Grava a opinião
   Update siw_solicitacao set opiniao = p_opiniao, motivo_insatisfacao = p_motivo Where sq_siw_solicitacao = p_chave;

end SP_PutSolicOpiniao;
/
