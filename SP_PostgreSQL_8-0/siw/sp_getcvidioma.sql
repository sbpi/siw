create or replace FUNCTION SP_GetCVIdioma
   (p_usuario   numeric,
    p_chave     numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados de formação acadêmica do colaborador
   open p_result for 
      select a.sq_pessoa, a.sq_idioma, a.leitura, a.escrita, a.compreensao, a.conversacao,
             case a.escrita     when 'S' then 'Com facilidade' else 'Com dificuldade' end nm_escrita,
             case a.leitura     when 'S' then 'Com facilidade' else 'Com dificuldade' end nm_leitura,
             case a.conversacao when 'S' then 'Com fluência'   else 'Sem fluência'    end nm_conversacao,
             case a.compreensao when 'S' then 'Com facilidade' else 'Com dificuldade' end nm_compreensao,
             b.nome, b.ativo, b.padrao
        from cv_pessoa_idioma     a
             inner join co_idioma b on (a.sq_idioma = b.sq_idioma)
       where a.sq_pessoa         = p_usuario
         and ((p_chave           is null) or (p_chave is not null and a.sq_idioma = p_chave));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;