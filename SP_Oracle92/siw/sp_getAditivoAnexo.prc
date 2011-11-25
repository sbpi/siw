create or replace procedure SP_GetAditivoAnexo
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_arquivo   in number   default null,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera as demandas que o usuário pode ver
   open p_result for 
      select c.sq_siw_solicitacao as chave,
             a.sq_acordo_aditivo  as chave_aux,
             b.sq_siw_arquivo     as arquivo, b.nome, b.descricao, 
             b.inclusao, b.tamanho, b.tipo, b.caminho, b.nome_original
        from ac_aditivo_arquivo            a
             inner join siw_arquivo        b on (a.sq_siw_arquivo    = b.sq_siw_arquivo)
             inner join ac_acordo_aditivo  c on (a.sq_acordo_aditivo = c.sq_acordo_aditivo)
       where c.sq_siw_solicitacao = p_chave
         and a.sq_acordo_aditivo  = p_chave_aux
         and ((p_arquivo          is null) or (p_arquivo   is not null and b.sq_siw_arquivo    = p_arquivo));
End SP_GetAditivoAnexo;
/
