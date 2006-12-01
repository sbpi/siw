create or replace procedure SP_PutOpiniao
   (p_operacao     in  varchar2,
    p_chave        in  number    default null,
    p_cliente      in  number,
    p_nome         in  varchar2,
    p_sigla        in  varchar2,    
    p_ordem        in  number
   ) is
   
begin
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      insert into siw_opiniao
        (sq_siw_opiniao,        cliente,    nome,         sigla,        ordem)
      values
        (sq_siw_opiniao.nextVal, p_cliente, trim(p_nome), trim(p_sigla), p_ordem);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_opiniao
         set cliente           = p_cliente,
             nome              = trim(p_nome),
             sigla             = trim(p_sigla),             
             ordem             = p_ordem
       where sq_siw_opiniao = p_chave;
   Elsif p_operacao = 'E' Then
      delete siw_opiniao
       where sq_siw_opiniao = p_chave;
   End If;
end SP_PutOpiniao;
/
