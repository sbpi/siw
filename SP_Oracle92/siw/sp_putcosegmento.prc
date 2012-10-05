create or replace procedure SP_PutCOSegmento
   (p_operacao         in  varchar2,
    p_chave            in  number   default null,
    p_sigla            in  varchar2 default null,
    p_nome             in  varchar2 default null,
    p_padrao           in  varchar2 default null,
    p_ativo            in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_segmento (sq_segmento, sigla, nome, padrao,ativo)
      (select sq_segmento.nextval, upper(p_sigla), p_nome, p_padrao, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_segmento set
         sigla     = upper(p_sigla),
         nome      = p_nome,
         padrao    = p_padrao,
         ativo     = p_ativo
      where sq_segmento   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete co_segmento where sq_segmento = p_chave;
   End If;
end SP_PutCOSegmento;
/
