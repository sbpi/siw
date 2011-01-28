create or replace FUNCTION SP_GetTramiteData
   (p_chave      numeric,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados de um trâmite
   open p_result for
      select a.nome, a.ordem, a.sigla, a.ativo, a.descricao, a.chefia_imediata, b.acesso_geral,
             envia_mail, solicita_cc, a.destinatario, a.beneficiario_cumpre, a.gestor_cumpre, a.assina_tramite_anterior,
             (select min(sq_siw_tramite) from siw_tramite where sq_menu = b.sq_menu) as primeiro,
             (select count(*) from siw_tramite_fluxo w inner join siw_tramite x on (w.sq_siw_tramite_destino = x.sq_siw_tramite) where w.sq_siw_tramite_origem = a.sq_siw_tramite and x.ordem < a.ordem and x.ativo = 'S') as qtd_ant,
             (select count(*) from siw_tramite_fluxo w inner join siw_tramite x on (w.sq_siw_tramite_destino = x.sq_siw_tramite) where w.sq_siw_tramite_origem = a.sq_siw_tramite and x.ordem > a.ordem and x.ativo = 'S') as qtd_pos,
             (select count(*) from siw_tramite_fluxo w inner join siw_tramite x on (w.sq_siw_tramite_destino = x.sq_siw_tramite) where w.sq_siw_tramite_origem = a.sq_siw_tramite and x.ordem = a.ordem and x.ativo = 'S') as qtd_ord
      from siw_tramite         a
           inner join siw_menu b on (a.sq_menu        = b.sq_menu)
      where a.sq_siw_tramite= p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;