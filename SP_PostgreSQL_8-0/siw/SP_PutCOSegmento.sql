create or replace function SP_PutCOSegmento
   (p_operacao         varchar,
    p_chave            numeric,
    p_nome             varchar,
    p_ativo            varchar,
    p_padrao           varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_segmento (sq_segmento, nome, padrao,ativo)
      (select nextval('sq_segmento'),
              trim(p_nome),
              trim(p_padrao),
              trim(p_ativo)
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_segmento set
         nome      = trim(p_nome),
         padrao    = p_padrao,
         ativo     = p_ativo
      where sq_segmento   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_segmento where sq_segmento = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;