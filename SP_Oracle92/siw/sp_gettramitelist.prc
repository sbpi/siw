create or replace procedure SP_GetTramiteList
   (p_chave     in  number,
    p_restricao in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_result    out sys_refcursor
   ) is
begin
   If p_restricao is null Then
      -- Recupera os dados de um trâmite
      open p_result for
         select a.sq_siw_tramite, a.sq_menu, a.nome, a.ordem, 
                a.sigla, a.descricao, a.chefia_imediata, a.ativo, a.solicita_cc, a.envia_mail,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante'
                   when 'U' then 'Chefia e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                end nm_chefia
         from siw_tramite a 
         where a.sq_menu = p_chave
           and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo))
        order by a.ordem;
   Elsif upper(p_restricao) = 'ERRO' Then
      open p_result for
         select a.sq_siw_tramite, a.sq_menu, a.nome, a.ordem,  
                a.sigla, a.descricao, a.chefia_imediata, a.ativo, a.solicita_cc, a.envia_mail,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante'
                   when 'U' then 'Chefia e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                end nm_chefia
         from siw_tramite                 a
         where a.sq_siw_tramite in (select sq_siw_tramite
                                      from siw_tramite b
                                     where b.sq_menu       = (select sq_menu from siw_tramite where sq_siw_tramite = p_chave)
                                       and b.ordem         <= (select ordem from siw_tramite where sq_siw_tramite = p_chave)
                                       and b.ativo = 'S'
                                   )
           and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo))                                   
        order by a.ordem;
   Elsif upper(p_restricao) = 'PROXIMO' Then
      open p_result for
         select b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem,
                b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante'
                   when 'U' then 'Chefia e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                end nm_chefia
         from siw_tramite            a
              inner join siw_tramite b on (a.sq_menu        = b.sq_menu and
                                           b.ordem          = a.ordem + 1
                                          )
         where a.sq_siw_tramite = p_chave
           and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));
   Elsif upper(p_restricao) = 'ANTERIOR' Then
      open p_result for
         select b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem,
                b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante'
                   when 'U' then 'Chefia e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                end nm_chefia
         from siw_tramite            a
              inner join siw_tramite b on (a.sq_menu        = b.sq_menu and
                                           b.ordem          = a.ordem - 1
                                          )
         where a.sq_siw_tramite = p_chave
           and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));
   Elsif upper(p_restricao) = 'DEVOLUCAO' Then
      open p_result for
         select b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem,
                b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante'
                   when 'U' then 'Chefia e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                end nm_chefia
         from siw_tramite              a
                inner join siw_tramite b on (a.sq_menu        = b.sq_menu)
         where a.sq_siw_tramite = p_chave
           and b.ordem          < (select ordem+1 from siw_tramite where sq_siw_tramite = p_chave)
           and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));           
   Else
      open p_result for
         select a.sq_siw_tramite, a.sq_menu, a.nome, a.ordem,  
                a.sigla, a.descricao, a.chefia_imediata, a.ativo, a.solicita_cc, a.envia_mail,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante'
                   when 'U' then 'Chefia e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                end nm_chefia
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
           and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo))                                   
        order by a.ordem;
   End If;
end SP_GetTramiteList;
/
