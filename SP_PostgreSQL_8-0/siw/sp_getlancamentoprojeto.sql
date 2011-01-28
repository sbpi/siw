create or replace FUNCTION SP_GetLancamentoProjeto
   (p_chave     numeric,
    p_menu      numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
     -- Recupera os lançamentos não cancelados e do tipo dotação inicial de um projeto 
     open p_result for 
       select b.sigla sg_tramite
         from siw_solicitacao a
              inner join siw_tramite   b on (a.sq_siw_tramite     = b.sq_siw_tramite and
                                             'CA'                 <> nvl(b.sigla,'--'))
              inner join fn_lancamento c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
        where a.sq_solic_pai = p_chave
          and a.sq_menu      = p_menu
          and c.tipo         = 1;
   Elsif p_restricao = 'LANCAMENTOS' Then
     -- Recupera os lançamentos não cancelados que nao seja do tipo dotação inicial
     open p_result for 
       select b.sigla sg_tramite
         from siw_solicitacao a
              inner join siw_tramite   b on (a.sq_siw_tramite     = b.sq_siw_tramite and
                                             'CA'                 <> nvl(b.sigla,'--'))
              inner join fn_lancamento c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
        where a.sq_solic_pai = p_chave
          and a.sq_menu      = p_menu
          and c.tipo         <> 1;   
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;