create or replace procedure SP_GetTramiteData
   (p_chave     in  number,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados de um trâmite
   open p_result for
      select a.nome, a.ordem, a.sigla, a.ativo, a.descricao, a.chefia_imediata, b.acesso_geral,
             envia_mail, solicita_cc,
             c.primeiro
      from siw_tramite a, siw_menu b,
           (select sq_menu, min(sq_siw_tramite) primeiro from siw_tramite group by sq_menu) c
      where b.sq_menu        = c.sq_menu
        and a.sq_menu        = b.sq_menu
        and a.sq_siw_tramite = p_chave;
end SP_GetTramiteData;
/

