create or replace procedure SP_GetTipoDocumento
   (p_chave     in number   default null,
    p_cliente   in number,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os tipos de contrato do cliente
   open p_result for
      select a.sq_tipo_documento chave, a.nome, a.sigla, a.ativo,
             decode(a.ativo,'S','Sim','Não') nm_ativo
        from fn_tipo_documento   a
   where a.cliente           = p_cliente and
         ((p_chave is null) or (p_chave is not null and a.sq_tipo_documento = p_chave));
end SP_GetTipoDocumento;
/

