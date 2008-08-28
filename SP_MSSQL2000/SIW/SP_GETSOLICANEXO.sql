create procedure Sp_GetSolicAnexo
   (@p_chave     int,
    @p_chave_aux int   = null,
    @p_cliente   int
    
   ) as
begin
   -- Recupera as demandas que o usuário pode ver
   
      select a.sq_siw_solicitacao chave,
             b.sq_siw_arquivo chave_aux, b.cliente, b.nome, b.descricao, 
             b.inclusao, b.tamanho, b.tipo, b.caminho
        from siw_solic_arquivo      a
             inner join siw_arquivo b on (a.sq_siw_arquivo = b.sq_siw_arquivo)
       where a.sq_siw_solicitacao = @p_chave
         and b.cliente            = @p_cliente
         and ((@p_chave_aux        is null) or (@p_chave_aux is not null and b.sq_siw_arquivo = @p_chave_aux));
End 

