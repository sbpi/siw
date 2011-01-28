create or replace FUNCTION sp_getSolic_Vinculo
   (p_cliente      numeric,
    p_usuario      numeric,
    p_chave        numeric,
    p_restricao    varchar,
    p_result       REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null or p_restricao = 'MENU' Then
  -- Recupera os serviços que podem alocar o recurso
      open p_result for
        select a.sq_menu, a.nome, a.acesso_geral, a.ultimo_nivel, a.tramite, a.sigla,
               b.sigla sg_modulo, b.nome nm_modulo, c.sq_siw_solicitacao
          from siw_menu                     a
               inner join siw_modulo        b on (a.sq_modulo          = b.sq_modulo)
               left  join siw_solic_vinculo c on (a.sq_menu            = c.sq_menu and
                                                  c.sq_siw_solicitacao = p_chave
                                                  )
         where a.sq_pessoa = p_cliente
           and a.tramite   = 'S'
           and a.ativo     = 'S'
        order by acentos(b.nome), acentos(a.nome);
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;