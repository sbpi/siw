create or replace procedure SP_PutSolicOpiniao
   (p_chave               in number,
    p_opiniao             in number
   ) is
begin
   -- Grava a opini�o
   Update siw_solicitacao set opiniao = p_opiniao Where sq_siw_solicitacao = p_chave;

end SP_PutSolicOpiniao;
/
