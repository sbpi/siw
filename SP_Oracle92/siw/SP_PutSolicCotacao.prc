create or replace procedure SP_PutSolicCotacao
   (p_operacao     in  varchar2,
    p_solic        in  number,
    p_moeda        in  number   default null,
    p_valor        in  number   default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_solic_cotacao (sq_siw_solicitacao, sq_moeda, valor) values (p_solic, p_moeda, p_valor);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_solic_cotacao
         set valor = p_valor
       where sq_siw_solicitacao = p_solic
         and sq_moeda           = p_moeda;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete siw_solic_cotacao where sq_siw_solicitacao = p_solic and (p_moeda is null or (p_moeda is not null and sq_moeda = p_moeda));
   End If;
end SP_PutSolicCotacao;
/
