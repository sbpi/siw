create or replace procedure SP_PutProjetoAcaoPPA
   (p_operacao                 in  varchar2,
    p_chave                    in  number  ,
    p_sq_acao_ppa              in  number   default null,
    p_observacao               in  varchar2 default null
   ) is
begin
   if p_operacao = 'I' Then
      -- Insere os registro
      insert into or_acao_financ(sq_siw_solicitacao, sq_acao_ppa, observacao) 
                  (select p_chave, p_sq_acao_ppa, p_observacao from dual);
   Elsif p_operacao = 'A' Then
      -- Altera os resgitros
      update or_acao_financ set
        observacao  = p_observacao
      where sq_siw_solicitacao = p_chave and sq_acao_ppa = p_sq_acao_ppa;
   Elsif p_operacao = 'E' Then
      -- Apaga os registro
      delete or_acao_financ where sq_siw_solicitacao = p_chave and sq_acao_ppa = p_sq_acao_ppa;
   End If;
end SP_PutProjetoAcaoPPA;
/

