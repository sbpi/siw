create or replace procedure SP_GetLcSituacao
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out sys_refcursor) is
begin
   -- Recupera as situa��es de licita��o
   open p_result for 
      select a.sq_lcsituacao chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao, a.publicar,
             case a.publicar  when 'S' then 'Sim' else 'N�o' end nm_publicar,
             case a.ativo     when 'S' then 'Sim' else 'N�o' end nm_ativo,
             case a.padrao    when 'S' then 'Sim' else 'N�o' end nm_padrao
        from lc_situacao a
       where a.cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_lcsituacao = p_chave));
end SP_GetLcSituacao;
/

