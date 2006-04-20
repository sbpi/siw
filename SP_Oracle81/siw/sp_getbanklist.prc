create or replace procedure SP_GetBankList
   (p_codigo    in  varchar2 default null,
    p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os bancos existentes
   open p_result for
      select sq_banco, codigo, nome, ativo, codigo||' - '||nome descricao, padrao
        from co_banco
       where (p_nome   is null or (p_nome   is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_codigo is null or (p_codigo is not null and codigo = p_codigo))
         and (p_ativo  is null or (p_ativo  is not null and ativo  = p_ativo))        
      order by padrao desc, codigo;
end SP_GetBankList;
/
