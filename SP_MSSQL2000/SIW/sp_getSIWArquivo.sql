alter procedure dbo.Sp_GetSIWArquivo
   (@p_cliente      int,
    @p_chave        int   =null,
    @p_restricao    varchar(50) =null
   ) as
begin
   If @p_restricao is null begin
      -- Recupera um ou todos os arquivos de um cliente
         select a.sq_siw_arquivo, a.cliente, a.nome, a.descricao, a.inclusao, a.tamanho, a.tipo, a.caminho,
                a.nome_original
           from siw_arquivo a
          where a.cliente  = @p_cliente
            and ((@p_chave  is null) or (@p_chave is not null and a.sq_siw_arquivo = @p_chave));
   End
end