create or replace procedure SP_GetTramiteList
   (p_chave     in  number,
    p_solic     in  number   default null,
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
                a.destinatario,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                   when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                   when 'I' then 'Todos os usuários internos'
                end nm_chefia
         from siw_tramite a 
         where a.sq_menu = p_chave
           and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo))
        order by a.ordem;
   Elsif upper(p_restricao) = 'FLUXO' Then
      open p_result for
         select a.sq_siw_tramite_origem, a.sq_siw_tramite_destino,
                b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem, 
                b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                b.destinatario,
                case b.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                   when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                   when 'I' then 'Todos os usuários internos'
                end nm_chefia
           from siw_tramite_fluxo      a
                inner join siw_tramite b on (a.sq_siw_tramite_destino = b.sq_siw_tramite)
          where a.sq_siw_tramite_origem = p_chave;
   Elsif upper(p_restricao) = 'DEVFLUXO' Then
      open p_result for
         select a.sq_siw_tramite_origem, a.sq_siw_tramite_destino,
                b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem, 
                b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                b.destinatario,
                case b.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                   when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                   when 'I' then 'Todos os usuários internos'
                end nm_chefia
           from siw_tramite_fluxo      a
                inner join siw_tramite b on (a.sq_siw_tramite_destino = b.sq_siw_tramite),
                siw_tramite            c
          where a.sq_siw_tramite_origem = p_chave
            and c.sq_siw_tramite        = p_chave
            and b.ordem                 < c.ordem
         MINUS
         (select a.sq_siw_tramite_origem, a.sq_siw_tramite_destino,
                 b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem, 
                 b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                 b.destinatario,
                 case b.chefia_imediata
                    when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                    when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                    when 'N' then 'Apenas usuários com permissão'
                    when 'I' then 'Todos os usuários internos'
                 end nm_chefia
            from siw_tramite_fluxo                a
                 inner   join siw_tramite         b  on (a.sq_siw_tramite_destino = b.sq_siw_tramite),
                 siw_tramite                      c
                 inner   join siw_solicitacao     d  on (c.sq_siw_tramite         = d.sq_siw_tramite)
                   inner join siw_menu            e  on (d.sq_menu                = e.sq_menu)
                   inner join pd_missao           f  on (d.sq_siw_solicitacao     = f.sq_siw_solicitacao)
                   inner join pd_categoria_diaria g  on (f.diaria                  = g.sq_categoria_diaria)
           where a.sq_siw_tramite_origem = p_chave
             and c.sq_siw_tramite        = p_chave
             and d.sq_siw_solicitacao    = p_solic
             and e.sq_pessoa             = 10135 -- Abdi
             and g.tramite_especial      <> 'S'
             and b.sigla                 = 'PR'  -- Tramite de reservas pelo gabinete
          UNION
          select a.sq_siw_tramite_origem, a.sq_siw_tramite_destino,
                 b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem, 
                 b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                 b.destinatario,
                 case b.chefia_imediata
                    when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                    when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                    when 'N' then 'Apenas usuários com permissão'
                    when 'I' then 'Todos os usuários internos'
                 end nm_chefia
            from siw_tramite_fluxo            a
                 inner   join siw_tramite     b on (a.sq_siw_tramite_destino = b.sq_siw_tramite),
                 siw_tramite                  c
                 inner   join siw_solicitacao d on (c.sq_siw_tramite         = d.sq_siw_tramite)
                   inner join siw_menu        e on (d.sq_menu                = e.sq_menu)
                   inner join pd_missao       f on (d.sq_siw_solicitacao     = f.sq_siw_solicitacao)
           where a.sq_siw_tramite_origem = p_chave
             and c.sq_siw_tramite        = p_chave
             and d.sq_siw_solicitacao    = p_solic
             and e.sq_pessoa             = 10135 -- Abdi
             and f.internacional         = 'N'   -- Viagem nacional
             and b.sigla                 = 'DF'
         ); -- Tramite de cotação de preços
   Elsif upper(p_restricao) = 'DEVOLUCAO' Then
      open p_result for
         select b.sq_siw_tramite, b.sq_menu, b.nome, b.ordem,
                b.sigla, b.descricao, b.chefia_imediata, b.ativo, b.solicita_cc, b.envia_mail,
                a.destinatario,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                   when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                   when 'I' then 'Todos os usuários internos'
                end nm_chefia
         from siw_tramite              a
                inner join siw_tramite b on (a.sq_menu        = b.sq_menu)
         where a.sq_siw_tramite = p_chave
           and b.ordem          < (select ordem+1 from siw_tramite where sq_siw_tramite = p_chave)
           and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));           
   Elsif upper(p_restricao) = 'ERRO' Then
      open p_result for
         select a.sq_siw_tramite, a.sq_menu, a.nome, a.ordem,  
                a.sigla, a.descricao, a.chefia_imediata, a.ativo, a.solicita_cc, a.envia_mail,
                a.destinatario,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                   when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                   when 'I' then 'Todos os usuários internos'
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
                a.destinatario,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                   when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                   when 'I' then 'Todos os usuários internos'
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
                a.destinatario,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                   when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                   when 'I' then 'Todos os usuários internos'
                end nm_chefia
         from siw_tramite            a
              inner join siw_tramite b on (a.sq_menu        = b.sq_menu and
                                           b.ordem          = a.ordem - 1
                                          )
         where a.sq_siw_tramite = p_chave
           and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));
   Else
      open p_result for
         select a.sq_siw_tramite, a.sq_menu, a.nome, a.ordem,  
                a.sigla, a.descricao, a.chefia_imediata, a.ativo, a.solicita_cc, a.envia_mail,
                a.destinatario,
                case a.chefia_imediata
                   when 'S' then 'Chefia da unidade solicitante e usuários com  permissão'
                   when 'U' then 'Chefia da unidade responsável e usuários com  permissão'
                   when 'N' then 'Apenas usuários com permissão'
                   when 'I' then 'Todos os usuários internos'
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
