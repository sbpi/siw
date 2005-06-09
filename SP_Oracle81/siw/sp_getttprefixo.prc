create or replace procedure SP_GetTTPrefixo
   (p_chave   in  number   default null,
    p_prefixo in  varchar2 default null,
    p_uf      in  varchar2 default null,
    p_result  out siw.sys_refcursor) is
begin
   -- Recupera os tipos de índice
   open p_result for
        select a.sq_prefixo chave, a.prefixo, a.localidade, a.sigla, a.uf, a.ddd, a.controle, a.degrau
        from tt_prefixos a
        where ((p_chave   is null) or (p_chave   is not null and a.sq_prefixo = p_chave))
         and  ((p_prefixo is null) or (p_prefixo is not null and upper(a.prefixo) like '%'||upper(p_prefixo)||'%'))
         and  ((p_uf      is null) or (p_uf      is not null and upper(a.uf)      like '%'||upper(p_uf)||'%'));
end SP_GetTTPrefixo;
/

