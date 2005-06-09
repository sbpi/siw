create or replace procedure SP_GetTramiteData
   (p_chave     in  number,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera os dados de um trâmite
   open p_result for
      select a.nome, a.ordem, a.sigla, a.ativo, a.descricao, a.chefia_imediata, b.acesso_geral,
             envia_mail, solicita_cc,
             (select min(sq_siw_tramite) from siw_tramite where sq_menu = b.sq_menu) primeiro
      from siw_tramite a, siw_menu b
      where a.sq_menu        = b.sq_menu
        and a.sq_siw_tramite= p_chave;
end SP_GetTramiteData;
/

