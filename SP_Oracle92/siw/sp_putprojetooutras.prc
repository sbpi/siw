create or replace procedure SP_PutProjetoOutras
   (p_operacao                 in  varchar2,
    p_chave                    in  number,
    p_sq_orprioridade          in  number default null
   ) is
begin
   if p_operacao = 'I' Then
      -- Insere os registro
      insert into or_acao_prioridade(sq_siw_solicitacao, sq_orprioridade) 
                  (select p_chave, p_sq_orprioridade from dual);

   Elsif p_operacao = 'E' Then
      -- Apaga os registro
      if p_sq_orprioridade is null then
         delete or_acao_prioridade where sq_siw_solicitacao = p_chave;
      Else
         delete or_acao_prioridade where sq_siw_solicitacao = p_chave and sq_orprioridade = p_sq_orprioridade;
      End If;   
   End If;
end SP_PutProjetoOutras;
/

