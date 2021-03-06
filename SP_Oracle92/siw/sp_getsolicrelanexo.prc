create or replace procedure SP_GetSolicRelAnexo
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_cliente   in number,
    p_tipo      in varchar2,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera as demandas que o usu�rio pode ver
   open p_result for 
      select a.sq_siw_solicitacao chave,
             b.sq_siw_arquivo chave_aux, b.cliente, b.nome, b.descricao, 
             b.inclusao, b.tamanho, b.tipo, b.caminho, b.nome_original, 
             a.tipo as tipo_reg
        from pd_missao_relatorio      a
             inner join siw_arquivo b on (a.sq_siw_arquivo = b.sq_siw_arquivo)
       where a.sq_siw_solicitacao = p_chave
         and b.cliente            = p_cliente
         and a.tipo               = p_tipo
         and ((p_chave_aux        is null) or (p_chave_aux is not null and b.sq_siw_arquivo = p_chave_aux));
End SP_GetSolicRelAnexo;
/
