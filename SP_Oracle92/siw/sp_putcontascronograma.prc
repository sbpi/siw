create or replace procedure SP_PutContasCronograma
   (p_operacao                 in varchar2,
    p_chave                    in number  default null,
    p_siw_solicitacao          in number,
    p_prestacao_contas         in number, 
    p_inicio                   in date,
    p_fim                      in date,
    p_limite                   in date
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_contas_cronograma
          (sq_contas_cronograma,         sq_siw_solicitacao, sq_prestacao_contas, inicio,   fim,   limite)
        values
          (sq_contas_cronograma.nextval, p_siw_solicitacao,  p_prestacao_contas,  p_inicio, p_fim, p_limite);

   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_contas_cronograma
         set inicio           = p_inicio,
             fim              = p_fim,
             limite           = p_limite
       where sq_contas_cronograma = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete siw_contas_registro   where sq_contas_cronograma = p_chave;
      delete siw_contas_cronograma where sq_contas_cronograma = p_chave;
   End If;
end SP_PutContasCronograma;
/
