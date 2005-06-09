create or replace procedure SP_GetKnowArea
   (p_chave     in number    default null,
    p_nome      in varchar2  default null,
    p_tipo      in varchar2,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os dados de áreas do conhecimento
   open p_result for
      select sq_area_conhecimento, sq_area_conhecimento_pai, nome, codigo_cnpq,
             especializacao, hierarquia, tecnico, requisito, ativo
        from co_area_conhecimento a
       where ((p_chave        is null) or (p_chave is not null and a.sq_area_conhecimento = p_chave))
         and ((p_nome         is null) or (p_nome is not null and acentos(a.nome,2) like '%'||acentos(p_nome,2)||'%'))
         and a.especializacao = p_tipo;
end SP_GetKnowArea;
/

