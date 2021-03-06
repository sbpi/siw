alter procedure dbo.SP_GetTramiteData (@p_chave int) as
begin
   -- Recupera os dados de um trâmite
      select a.nome, a.ordem, a.sigla, a.ativo, a.descricao, a.chefia_imediata, b.acesso_geral,
             envia_mail, solicita_cc,
             (select min(sq_siw_tramite) from siw_tramite where sq_menu = b.sq_menu) primeiro
      from siw_tramite a, siw_menu b
      where a.sq_menu        = b.sq_menu
        and a.sq_siw_tramite = @p_chave
end