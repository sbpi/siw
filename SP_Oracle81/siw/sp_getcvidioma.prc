create or replace procedure SP_GetCVIdioma
   (p_usuario   in number,
    p_chave     in number    default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os dados de formação acadêmica do colaborador
   open p_result for
      select a.sq_pessoa, a.sq_idioma, a.leitura, a.escrita, a.compreensao, a.conversacao,
             decode(a.escrita     , 'S' , 'Com facilidade' , 'Com dificuldade') nm_escrita,
             decode(a.leitura     , 'S' , 'Com facilidade' , 'Com dificuldade') nm_leitura,
             decode(a.conversacao , 'S' , 'Com fluência'   , 'Sem fluência'   ) nm_conversacao,
             decode(a.compreensao , 'S' , 'Com facilidade' , 'Com dificuldade') nm_compreensao,
             b.nome, b.ativo, b.padrao
        from cv_pessoa_idioma     a,
             co_idioma b
       where (a.sq_idioma = b.sq_idioma)
         and a.sq_pessoa         = p_usuario
         and ((p_chave           is null) or (p_chave is not null and a.sq_idioma = p_chave));
end SP_GetCVIdioma;
/

