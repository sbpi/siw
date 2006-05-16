create or replace procedure SP_GetTramiteResp
   (p_solic     in number,
    p_tramite   in number default null,
    p_restricao in varchar2 default null,
    p_result    out siw.sys_refcursor
   ) is

   l_tramite     number(18) := null;
   l_menu        number(18);
   l_vinculacao  varchar2(1);
   l_string      varchar2(255);
   l_solicitante number(18);
   l_cadastrador number(18);
   l_unid_solic  number(18);
   l_unid_usu    number(18);
   l_unidade     number(18);
   l_item        varchar2(18);
   l_chefe1      number(18) := 0;
   l_chefe2      number(18) := 0;
   
begin
   -- Recupera os dados da solicitação para recuperaçao do trâmite
   select a.sq_menu, a.sq_siw_tramite, b.vinculacao, a.solicitante, a.cadastrador, a.sq_unidade, c.sq_unidade
     into l_menu,    l_tramite,        l_vinculacao, l_solicitante, l_cadastrador, l_unid_solic, l_unid_usu
     from siw_solicitacao a,
          siw_menu        b,
          sg_autenticacao c
    where a.sq_menu          = b.sq_menu
      and a.solicitante      = c.sq_pessoa
      and sq_siw_solicitacao = p_solic;
   
   -- Retorna os responsáveis pela unidade da solicitação ou do usuário, dependendo do tipo de vinculação
   If l_vinculacao = 'U' 
      Then l_unidade := l_unid_solic;
      Else l_unidade := l_unid_usu;
   End If;
   l_string := Responsavel_Unidade(l_unidade, l_solicitante, l_vinculacao);

   If l_string is not null Then
      for i in 1..2 Loop
         l_item  := Trim(substr(l_string,1,Instr(l_string,',')-1));
         If Length(l_item) > 0 Then
            If i = 1 
               Then l_chefe1 := to_number(Nvl(l_item,0));
               Else l_chefe2 := to_number(Nvl(l_item,0));
            End If;
         End If;
         l_string := substr(l_string,Instr(l_string,',')+1,200);
         Exit when l_string is null;
      End Loop;
   End If;
   
   -- Trata a situação de devolver os responsáveis de todos os trâmites
   If p_restricao = 'TODOS' Then
     l_tramite := null;
   Else
      -- Se a chamada receber um trâmite, usa ele ao invés do trâmite atual da solicitação
      If p_tramite is not null Then l_tramite := p_tramite; End If;
   End If;
   
   open p_result for
     -- Recupera pessoas que têm permissão para cumprimento do trâmite
     select a.sq_siw_tramite, a.sq_menu, a.nome as nm_tramite, a.ordem, a.chefia_imediata,
            c.nome, c.nome_resumido, 
            d.sq_pessoa, d.username, d.email, 
            e.nome as nm_unidade, e.sigla as sg_unidade,
            acesso(p_solic, d.sq_pessoa) as acesso,
            'Permissão' as tipo
       from siw_tramite       a,
            sg_tramite_pessoa b,
            co_pessoa         c,
            sg_autenticacao   d,
            eo_unidade        e
      where a.sq_siw_tramite  = b.sq_siw_tramite
        and b.sq_pessoa       = c.sq_pessoa
        and c.sq_pessoa       = d.sq_pessoa
        and d.sq_unidade      = e.sq_unidade
        and a.sq_menu         = l_menu
        and a.chefia_imediata in ('N', 'U')
        and a.sigla           <> 'CI'
        and d.ativo           = 'S'
        and (l_tramite        is null or (l_tramite is not null and b.sq_siw_tramite = l_tramite))
     UNION
     -- Recupera os dados do cadastrador da solicitação
     select b.sq_siw_tramite, b.sq_menu, b.nome as nm_tramite, b.ordem, b.chefia_imediata,
            d.nome, d.nome_resumido, d.sq_pessoa, d.username, d.email, 
            c.nome as nm_unidade, c.sigla as sg_unidade,
            acesso(p_solic, d.sq_pessoa) as acesso,
            'Cadastrador' as tipo
       from siw_tramite       b,
            eo_unidade        c,
            (select y.sq_pessoa, y.nome, y.nome_resumido, z.sq_unidade, z.username, z.email
               from co_pessoa         y,
                    sg_autenticacao   z
              where y.sq_pessoa    = z.sq_pessoa
                and y.sq_pessoa    = l_cadastrador
            )                 d
      where c.sq_unidade         = d.sq_unidade
        and b.sq_menu            = l_menu
        and b.sigla              = 'CI'
        and (l_tramite           is null or (l_tramite is not null and b.sq_siw_tramite = l_tramite))
     UNION
     -- Recupera os dados do titular e do substituto da unidade à qual o solicitante ou a solicitação estão vinculados
     select b.sq_siw_tramite, b.sq_menu, b.nome as nm_tramite, b.ordem, b.chefia_imediata,
            d.nome, d.nome_resumido, d.sq_pessoa, d.username, d.email, 
            c.nome as nm_unidade, c.sigla as sg_unidade,
            acesso(p_solic, d.sq_pessoa) as acesso,
            'Chefia' as tipo
       from siw_tramite       b,
            eo_unidade        c,
            (select y.sq_pessoa, y.nome, y.nome_resumido, z.sq_unidade, z.username, z.email
               from co_pessoa         y,
                    sg_autenticacao   z
              where y.sq_pessoa    = z.sq_pessoa
                and (y.sq_pessoa    = l_chefe1 or y.sq_pessoa = l_chefe2)
            )                 d
      where c.sq_unidade         = d.sq_unidade
        and b.sq_menu            = l_menu
        and b.chefia_imediata    = 'S'
        and (l_tramite           is null or (l_tramite is not null and b.sq_siw_tramite = l_tramite))
     UNION
     -- Recupera os dados do titular da unidade executora
     select b.sq_siw_tramite, b.sq_menu, b.nome as nm_tramite, b.ordem, b.chefia_imediata,
            d.nome, d.nome_resumido, d.sq_pessoa, d.username, d.email, 
            c.nome as nm_unidade, c.sigla as sg_unidade,
            acesso(p_solic, d.sq_pessoa) as acesso,
            'Titular Executora' as tipo
       from siw_solicitacao   a,
            siw_menu          e,
            siw_tramite       b,
            eo_unidade        c,
            (select x.sq_unidade, x.sq_pessoa, y.nome, y.nome_resumido, z.username, z.email
               from eo_unidade_resp   x,
                    co_pessoa         y,
                    sg_autenticacao   z
              where x.sq_pessoa    = y.sq_pessoa
                and y.sq_pessoa    = z.sq_pessoa
                and x.fim          is null
                and x.tipo_respons = 'T'
                and z.ativo        = 'S'
            )                 d
      where a.sq_menu            = e.sq_menu
        and e.sq_menu            = b.sq_menu
        and e.sq_unid_executora  = c.sq_unidade
        and c.sq_unidade         = d.sq_unidade
        and e.sq_menu            = l_menu
        and b.chefia_imediata    = 'U'
        and a.sq_siw_solicitacao = p_solic
        and (l_tramite           is null or (l_tramite is not null and b.sq_siw_tramite = l_tramite))
     UNION
     -- Recupera os dados do substituto da unidade executora
     select b.sq_siw_tramite, b.sq_menu, b.nome as nm_tramite, b.ordem, b.chefia_imediata,
            d.nome, d.nome_resumido, d.sq_pessoa, d.username, d.email,
            c.nome as nm_unidade, c.sigla as sg_unidade,
            acesso(p_solic, d.sq_pessoa) as acesso,
            'Sustituto Executora' as tipo
       from siw_solicitacao   a,
            siw_menu          e,
            siw_tramite       b,
            eo_unidade        c,
            (select x.sq_unidade, x.sq_pessoa, y.nome, y.nome_resumido, z.username, z.email
               from eo_unidade_resp   x,
                    co_pessoa         y,
                    sg_autenticacao   z
              where x.sq_pessoa    = y.sq_pessoa
                and y.sq_pessoa    = z.sq_pessoa
                and x.fim          is null
                and x.tipo_respons = 'S'
                and z.ativo        = 'S'
            )                 d
      where a.sq_menu            = e.sq_menu
        and e.sq_menu            = b.sq_menu
        and e.sq_unid_executora  = c.sq_unidade
        and c.sq_unidade         = d.sq_unidade
        and e.sq_menu            = l_menu
        and b.chefia_imediata    = 'U'
        and a.sq_siw_solicitacao = p_solic
        and (l_tramite           is null or (l_tramite is not null and b.sq_siw_tramite = l_tramite))
     order by 4,7;
end SP_GetTramiteResp;
/
