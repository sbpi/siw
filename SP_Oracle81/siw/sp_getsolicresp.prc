create or replace procedure SP_GetSolicResp
   (p_chave        in number   default null,
    p_tramite      in number   default null,
    p_fase         in varchar2 default null,
    p_restricao    in varchar2,
    p_result       out siw.sys_refcursor) is

    l_item       varchar2(18);
    l_fase       varchar2(200) := p_fase ||',';
    x_fase       varchar2(200) := '';
begin
   If p_fase is not null Then
      Loop
         l_item  := Trim(substr(l_fase,1,Instr(l_fase,',')-1));
         If Length(l_item) > 0 Then
            x_fase := x_fase||','''||to_number(l_item)||'''';
         End If;
         l_fase := substr(l_fase,Instr(l_fase,',')+1,200);
         Exit when l_fase is null;
      End Loop;
      x_fase := substr(x_fase,2,200);
   End If;

   If p_restricao = 'GENERICO' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for
         select distinct d.sq_pessoa, d.nome, d.nome_resumido,
                e.email, e.ativo ativo_usuario,
                f.sigla sg_unidade
           from siw_solicitacao                       a,
                siw_solic_log        b,
                siw_tramite          c,
                co_pessoa            d,
                sg_autenticacao      e,
                eo_unidade           f
          where (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            and (b.sq_siw_tramite     = c.sq_siw_tramite)
            and (b.sq_pessoa          = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa)
            and (e.sq_unidade         = f.sq_unidade)
            and b.sq_siw_solicitacao = p_chave
            and e.ativo              = 'S'
            and (p_fase              is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
         UNION
         select distinct b.sq_pessoa, b.nome, b.nome_resumido,
                c.email, c.ativo ativo_usuario,
                d.sigla sg_unidade
           from siw_solicitacao                     a,
                co_pessoa            b,
                sg_autenticacao      c,
                eo_unidade           d
          where (a.solicitante        = b.sq_pessoa)
            and (b.sq_pessoa          = c.sq_pessoa)
            and (c.sq_unidade         = d.sq_unidade)
            and a.sq_siw_solicitacao = p_chave
            and c.ativo              = 'S'
         UNION
         select distinct c.sq_pessoa, c.nome, c.nome_resumido,
                d.email, d.ativo ativo_usuario,
                e.sigla sg_unidade
           from siw_solicitacao                       a,
                eo_unidade_resp      b,
                co_pessoa            c,
                sg_autenticacao      d,
                eo_unidade           e
          where (a.sq_unidade         = b.sq_unidade)
            and (b.sq_pessoa          = c.sq_pessoa)
            and (c.sq_pessoa          = d.sq_pessoa)
            and (d.sq_unidade         = e.sq_unidade)
            and a.sq_siw_solicitacao = p_chave
            and b.tipo_respons       = 'T'
            and b.fim                is null
            and d.ativo              = 'S'
         UNION
         select distinct c.sq_pessoa, c.nome, c.nome_resumido,
                d.email, d.ativo ativo_usuario,
                e.sigla sg_unidade
           from siw_solicitacao                       a,
                eo_unidade_resp      b,
                co_pessoa            c,
                sg_autenticacao      d,
                eo_unidade           e
          where (a.sq_unidade         = b.sq_unidade)
            and (b.sq_pessoa          = c.sq_pessoa)
            and (c.sq_pessoa          = d.sq_pessoa)
            and (d.sq_unidade         = e.sq_unidade)
            and a.sq_siw_solicitacao = p_chave
            and b.tipo_respons       = 'S'
            and d.ativo              = 'S'
            and b.fim                is null;
   ElsIf p_restricao = 'CADASTRAMENTO' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for
         select distinct d.sq_pessoa, d.nome, d.nome_resumido,
                e.email, e.ativo ativo_usuario,
                f.sigla sg_unidade
           from siw_solicitacao                       a,
                siw_solic_log        b,
                siw_tramite          c,
                co_pessoa            d,
                sg_autenticacao      e,
                eo_unidade           f
          where (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            and (b.sq_siw_tramite     = c.sq_siw_tramite)
            and (b.sq_pessoa          = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa)
            and (e.sq_unidade         = f.sq_unidade)
            and b.sq_siw_solicitacao = p_chave
            and c.sigla              = 'CI'
            and e.ativo              = 'S'
         UNION
         select distinct b.sq_pessoa, b.nome, b.nome_resumido,
                c.email, c.ativo ativo_usuario,
                d.sigla sg_unidade
           from siw_solicitacao                     a,
                co_pessoa            b,
                sg_autenticacao      c,
                eo_unidade           d
          where (a.solicitante        = b.sq_pessoa)
            and (b.sq_pessoa          = c.sq_pessoa)
            and (c.sq_unidade         = d.sq_unidade)
            and a.sq_siw_solicitacao = p_chave
            and c.ativo              = 'S'
         UNION
         select distinct c.sq_pessoa, c.nome, c.nome_resumido,
                d.email, d.ativo ativo_usuario,
                e.sigla sg_unidade
           from siw_solicitacao                       a,
                eo_unidade_resp      b,
                co_pessoa            c,
                sg_autenticacao      d,
                eo_unidade           e
          where (a.sq_unidade         = b.sq_unidade)
            and (b.sq_pessoa          = c.sq_pessoa)
            and (c.sq_pessoa          = d.sq_pessoa)
            and (d.sq_unidade         = e.sq_unidade)
            and a.sq_siw_solicitacao = p_chave
            and b.tipo_respons       = 'T'
            and b.fim                is null
            and d.ativo              = 'S'
         UNION
         select distinct c.sq_pessoa, c.nome, c.nome_resumido,
                d.email, d.ativo ativo_usuario,
                e.sigla sg_unidade
           from siw_solicitacao                       a,
                eo_unidade_resp      b,
                co_pessoa            c,
                sg_autenticacao      d,
                eo_unidade           e
          where (a.sq_unidade         = b.sq_unidade)
            and (b.sq_pessoa          = c.sq_pessoa)
            and (c.sq_pessoa          = d.sq_pessoa)
            and (d.sq_unidade         = e.sq_unidade)
            and a.sq_siw_solicitacao = p_chave
            and b.tipo_respons       = 'S'
            and d.ativo              = 'S'
            and b.fim                is null;
   ElsIf p_restricao = 'USUARIOS' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for
         select distinct d.sq_pessoa, d.nome, d.nome_resumido,
                e.email, e.ativo ativo_usuario,
                f.sigla sg_unidade
           from siw_tramite                           c,
                sg_tramite_pessoa    g,
                co_pessoa            d,
                sg_autenticacao      e,
                eo_unidade           f
          where (c.sq_siw_tramite     = g.sq_siw_tramite)
            and (g.sq_pessoa          = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa and
                 e.ativo              = 'S'
                )
            and (e.sq_unidade         = f.sq_unidade and
                 g.sq_pessoa_endereco = f.sq_pessoa_endereco
                )
            and c.sq_siw_tramite     = p_tramite
            and c.chefia_imediata    in ('U','N')
         UNION
         select distinct d.sq_pessoa, d.nome, d.nome_resumido,
                e.email, e.ativo ativo_usuario,
                f.sigla sg_unidade
           from siw_tramite                             c,
                siw_menu             a,
                sg_pessoa_modulo     g,
                co_pessoa            d,
                sg_autenticacao      e,
                eo_unidade           f
          where (c.sq_menu            = a.sq_menu)
            and (a.sq_modulo          = g.sq_modulo and
                 a.sq_pessoa          = g.cliente
                )
            and (g.sq_pessoa          = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa and
                 e.ativo              = 'S'
                )
            and (e.sq_unidade         = f.sq_unidade and
                 g.sq_pessoa_endereco = f.sq_pessoa_endereco
                )
            and c.sq_siw_tramite     = p_tramite
            and c.sigla              = 'CI'
         UNION
         select distinct
                decode(g.chefia_imediata,'U',c.sq_pessoa,i.sq_pessoa) sq_pessoa,
                decode(g.chefia_imediata,'U',c.nome,i.nome) nome,
                decode(g.chefia_imediata,'U',c.nome_resumido,i.nome_resumido) nome_resumido,
                decode(g.chefia_imediata,'U',d.email,j.email) email,
                decode(g.chefia_imediata,'U',d.ativo,j.ativo) ativo_usuario,
                decode(g.chefia_imediata,'U',e.sigla,k.sigla) sg_unidade
           from siw_tramite                           g,
                siw_menu             f,
                eo_unidade_resp      b,
                co_pessoa            c,
                sg_autenticacao     d,
                eo_unidade        e,
                siw_solicitacao                       a,
                eo_unidade_resp      h,
                co_pessoa            i,
                sg_autenticacao     j,
                eo_unidade      k
          where (g.sq_menu            = f.sq_menu)
            and (f.sq_unid_executora  = b.sq_unidade (+) and
                 b.fim (+)            is null
                )
            and (b.sq_pessoa          = c.sq_pessoa (+))
            and (c.sq_pessoa          = d.sq_pessoa (+) and
                 d.ativo (+)          = 'S'
                )
            and (d.sq_unidade         = e.sq_unidade (+))
            and (a.sq_unidade         = h.sq_unidade (+) and
                 h.fim (+)            is null
                )
            and (h.sq_pessoa          = i.sq_pessoa (+))
            and (i.sq_pessoa          = j.sq_pessoa (+) and
                 j.ativo (+)          = 'S'
                )
            and (j.sq_unidade         = k.sq_unidade (+))
            and  a.sq_siw_solicitacao = p_chave
            and g.chefia_imediata    in ('S','U')
            and g.sq_siw_tramite     = p_tramite
            and b.fim                is null;
   End If;
end SP_GetSolicResp;
/

