create or replace procedure SP_GetFormatList
   (p_tipo      in  varchar2 default null,
    p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os bancos existentes
   open p_result for
      select a.ordem, a.sq_formacao, a.nome, a.ativo, b.tipo,
             case a.ativo when 'S' then 'Sim' else 'Não' end ativodesc
        from co_formacao   a
             inner join (select sq_formacao, 
                                case tipo when '1' then 'Acadêmica' 
                                          when '2' then 'Técnica'
                                          else 'Prod.Cient.'
                                end tipo
                           from co_formacao
                         ) b on a.sq_formacao = b.sq_formacao
       where (p_tipo  is null or (p_tipo  is not null and b.tipo = p_tipo))
         and (p_nome  is null or (p_nome  is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));
end SP_GetFormatList;
/
