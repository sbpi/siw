create or replace procedure SP_GetArquivo
   (p_cliente      in  number,
    p_chave        in number   default null,
    p_sq_sistema   in number   default null,
    p_nome         in varchar2 default null,
    p_tipo_arquivo in varchar2 default null,
    p_result       out siw.sys_refcursor) is
begin
   -- Recupera os tipos de arquivos
   open p_result for
      select a.sq_arquivo chave, a.sq_sistema, a.nome nm_arquivo, a.descricao, a.tipo, a.diretorio,
             b.sigla sg_sistema, b.nome nm_sistema
        from dc_arquivo               a,
             dc_sistema b
       where (a.sq_sistema = b.sq_sistema)
         and b.cliente = p_cliente
         and ((p_chave        is null) or (p_chave        is not null and a.sq_arquivo = p_chave))
         and ((p_sq_sistema   is null) or (p_sq_sistema   is not null and a.sq_sistema = p_sq_sistema))
         and ((p_tipo_arquivo is null) or (p_tipo_arquivo is not null and a.tipo       = p_tipo_arquivo))
         and ((p_nome         is null) or (p_nome         is not null and upper(a.nome) like '%'||upper(p_nome)||'%'));
end SP_GetArquivo;
/

