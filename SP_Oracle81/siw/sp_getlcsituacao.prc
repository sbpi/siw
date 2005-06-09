create or replace procedure SP_GetLcSituacao
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as situa��es de licita��o
   open p_result for
      select a.sq_lcsituacao chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao, a.publicar,
             decode(a.publicar ,'S','Sim','N�o') nm_publicar,
             decode(a.ativo    ,'S','Sim','N�o') nm_ativo,
             decode(a.padrao   ,'S','Sim','N�o') nm_padrao
        from lc_situacao a
       where a.cliente = p_cliente
         and ((p_chave is null) or (p_chave is not null and a.sq_lcsituacao = p_chave));
end SP_GetLcSituacao;
/

