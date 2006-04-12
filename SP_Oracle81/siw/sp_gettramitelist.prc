create or replace procedure SP_GetTramiteList
   (p_chave     in  number,
    p_restricao in varchar2 default null,
    p_result    out siw.sys_refcursor
   ) is
begin
   If p_restricao is null Then
      -- Recupera os dados de um trâmite
      open p_result for
         select a.sq_siw_tramite, a.sq_menu, a.nome, a.ordem,
                a.sigla, a.descricao, a.chefia_imediata, a.ativo, a.solicita_cc, a.envia_mail,
                decode(a.chefia_imediata,'S','Chefia da unidade solicitante','U','Chefia e usuários com  permissão','N','Apenas usuários com permissão') nm_chefia
         from siw_tramite a
         where a.sq_menu = p_chave
        order by a.ordem;
   Elsif upper(p_restricao) = 'ERRO' Then
      open p_result for
         select a.sq_siw_tramite, a.sq_menu, a.nome, a.ordem,
                a.sigla, a.descricao, a.chefia_imediata, a.ativo, a.solicita_cc, a.envia_mail,
                decode(a.chefia_imediata,'S','Chefia da unidade solicitante','U','Chefia e usuários com  permissão','N','Apenas usuários com permissão') nm_chefia
         from siw_tramite                 a
         where a.sq_siw_tramite in (select sq_siw_tramite
                                      from siw_tramite b
                                     where b.sq_menu       = (select sq_menu from siw_tramite where sq_siw_tramite = p_chave)
                                       and b.ordem         <= (select ordem from siw_tramite where sq_siw_tramite = p_chave)
                                       and b.ativo = 'S'
                                   )
        order by a.ordem;
   Elsif upper(p_restricao) = 'PROXIMO' Then
      open p_result for
         select b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem,
                b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                decode(b.chefia_imediata,'S','Chefia da unidade solicitante','U','Chefia e usuários com  permissão','N','Apenas usuários com permissão') nm_chefia
         from siw_tramite a,
              siw_tramite b
         where a.sq_menu        = b.sq_menu
           and a.sq_siw_tramite = p_chave
           and b.ordem          = a.ordem + 1;
   Elsif upper(p_restricao) = 'ANTERIOR' Then
      open p_result for
         select b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem,
                b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                decode(b.chefia_imediata,'S','Chefia da unidade solicitante','U','Chefia e usuários com  permissão','N','Apenas usuários com permissão') nm_chefia
         from siw_tramite a,
              siw_tramite b
         where a.sq_menu        = b.sq_menu
           and a.sq_siw_tramite = p_chave
           and b.ordem          = a.ordem - 1;
   Else
      open p_result for
         select a.sq_siw_tramite, a.sq_menu, a.nome, a.ordem,
                a.sigla, a.descricao, a.chefia_imediata, a.ativo, a.solicita_cc, a.envia_mail,
                decode(a.chefia_imediata,'S','Chefia da unidade solicitante','U','Chefia e usuários com  permissão','N','Apenas usuários com permissão') nm_chefia
         from siw_tramite                 a
         where a.sq_siw_tramite in (select sq_siw_tramite
                                      from siw_tramite b
                                     where b.sq_siw_tramite = (select sq_siw_tramite from siw_solicitacao where sq_siw_solicitacao = p_restricao)
                                        or (b.sq_menu       = (select sq_menu from siw_solicitacao where sq_siw_solicitacao = p_restricao) and
                                            b.ordem         = (select ordem-1 from siw_tramite where sq_siw_tramite = (select sq_siw_tramite from siw_solicitacao where sq_siw_solicitacao = p_restricao)) and
                                            b.ativo = 'S'
                                           )
                                        or (b.sq_menu       = (select sq_menu from siw_solicitacao where sq_siw_solicitacao = p_restricao) and
                                            b.ordem         = (select ordem+1 from siw_tramite where sq_siw_tramite = (select sq_siw_tramite from siw_solicitacao where sq_siw_solicitacao = p_restricao)) and
                                            b.ativo = 'S'
                                           )
                                   )
        order by a.ordem;
   End If;
end SP_GetTramiteList;
/
