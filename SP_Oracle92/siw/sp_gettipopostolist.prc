create or replace procedure SP_GetTipoPostoList
   (p_cliente   in  number,
    p_chave     in  number,
    p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de postos existentes
   open p_result for 
      select a.sq_eo_tipo_posto, a.nome, a.padrao, 
             a.ativo, a.sigla, a.descricao
        from eo_tipo_posto a
       where a.cliente = p_cliente
       and ((p_chave is null) or (p_chave is not null and a.sq_eo_tipo_posto = p_chave))
     order by a.padrao, a.ativo, a.nome;
end SP_GetTipoPostoList;
/

