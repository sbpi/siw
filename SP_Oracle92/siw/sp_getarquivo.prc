create or replace procedure SP_GetArquivo
   (p_cliente      in  number,
    p_restricao    in varchar2 default null,
    p_chave        in number   default null,
    p_sq_sistema   in number   default null,
    p_nome         in varchar2 default null,
    p_diretorio    in varchar2 default null,
    p_tipo_arquivo in varchar2 default null,
    p_result       out sys_refcursor) is
begin
   -- Recupera os tipos de arquivos
   If p_restricao is null Then
     open p_result for 
        select a.sq_arquivo chave, a.sq_sistema, a.nome nm_arquivo, a.descricao, a.tipo, a.diretorio, 
               b.sigla sg_sistema, b.nome nm_sistema
          from dc_arquivo               a
               inner join    dc_sistema b on (a.sq_sistema = b.sq_sistema)
         where b.cliente = p_cliente
           and ((p_chave        is null) or (p_chave        is not null and a.sq_arquivo = p_chave))
           and ((p_sq_sistema   is null) or (p_sq_sistema   is not null and a.sq_sistema = p_sq_sistema))
           and ((p_tipo_arquivo is null) or (p_tipo_arquivo is not null and a.tipo       = p_tipo_arquivo))
           and ((p_diretorio    is null) or (p_diretorio    is not null and ((p_diretorio = '/' and a.diretorio is null) or (p_diretorio <> '/' and upper(a.diretorio) like '%'||upper(p_diretorio)||'%'))))
           and ((p_nome         is null) or (p_nome         is not null and upper(a.nome) like '%'||upper(p_nome)||'%'));
   Else
     open p_result for 
        select a.sq_arquivo chave, a.sq_sistema, a.nome nm_arquivo, a.descricao, a.tipo, a.diretorio, 
               b.sigla sg_sistema, b.nome nm_sistema
          from dc_arquivo               a
               inner join    dc_sistema b on (a.sq_sistema = b.sq_sistema)
         where b.cliente = p_cliente
           and ((p_chave        is null) or (p_chave        is not null and a.sq_arquivo = p_chave))
           and ((p_sq_sistema   is null) or (p_sq_sistema   is not null and a.sq_sistema = p_sq_sistema))
           and ((p_tipo_arquivo is null) or (p_tipo_arquivo is not null and a.tipo       = p_tipo_arquivo))
           and ((p_diretorio    is null  and a.diretorio is null) or 
                (p_diretorio is not null and ((p_diretorio = '/' and a.diretorio is null) or (p_diretorio <> '/' and upper(a.diretorio) = upper(p_diretorio))))
               )
           and ((p_nome         is null) or (p_nome         is not null and upper(a.nome) = upper(p_nome)));
   End If;
end SP_GetArquivo;
/
