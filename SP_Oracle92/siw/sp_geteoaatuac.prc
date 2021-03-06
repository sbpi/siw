create or replace procedure SP_GetEOAAtuac
   (p_sq_pessoa   in  number,
    p_nome        in  varchar2 default null,
    p_ativo       in  varchar2 default null,
    p_result      out sys_refcursor
   ) is
begin
   --Recupera a lista de �reas de atua��o
   open p_result for
      select sq_area_atuacao, nome, ativo
        from eo_area_atuacao
       where sq_pessoa = p_sq_pessoa
         and (p_nome  is null or (p_nome  is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
end SP_GetEOAAtuac;
/
