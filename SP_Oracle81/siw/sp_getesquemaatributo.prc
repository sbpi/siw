create or replace procedure SP_GetEsquemaAtributo
   (p_restricao           in varchar2  default null,
    p_sq_esquema_tabela   in number,
    p_sq_esquema_atributo in number    default null,
    p_sq_coluna           in number    default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as coluna cadastradas em uma tabela para importação
   open p_result for
      select a.sq_esquema_atributo, a.sq_esquema_tabela, a.sq_coluna, a.ordem, a.campo_externo,
             b.nome nm_coluna, b.tamanho, b.obrigatorio
        from dc_esquema_atributo    a,
             dc_coluna   b 
       where (a.sq_coluna = b.sq_coluna)
         and a.sq_esquema_tabela = p_sq_esquema_tabela
         and ((p_sq_esquema_atributo is null) or (p_sq_esquema_atributo is not null and a.sq_esquema_atributo = p_sq_esquema_tabela))
         and ((p_sq_coluna           is null) or (p_sq_coluna           is not null and a.sq_coluna           = p_sq_coluna));
end SP_GetEsquemaAtributo;
/

