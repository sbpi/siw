create or replace function SP_GetSiwArquivo
   (p_cliente      numeric,
    p_chave        numeric,
    p_restricao    varchar,
    p_result       refcursor
   ) returns refcursor as $$
begin
   If p_restricao is null Then
      -- Recupera um ou todos os arquivos de um cliente
      open p_result for 
         select a.sq_siw_arquivo, a.cliente, a.nome, a.descricao, a.inclusao, a.tamanho, a.tipo, a.caminho,
                a.nome_original
           from siw_arquivo a
          where a.cliente  = p_cliente
            and ((p_chave  is null) or (p_chave is not null and a.sq_siw_arquivo = p_chave));
   End If;
   return p_result;
end; $$ language 'plpgsql' volatile;