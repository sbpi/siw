create or replace procedure SP_GetEtniaList
   (p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera as etnias existentes
   open p_result for 
      select codigo_siape, sq_etnia, nome, ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end descativo 
        from co_etnia
       where (p_nome  is null or (p_nome  is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
end SP_GetEtniaList;
/
