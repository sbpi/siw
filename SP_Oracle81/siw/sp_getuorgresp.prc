create or replace procedure SP_GetUorgResp
   (p_chave      in  number,
    p_result     out siw.sys_refcursor
    ) is
begin
   -- Recupera os responsáveis titular e substituto da unidade selecionada
   open p_result for
     select a.sq_unidade, a.sq_unidade_pai, a.email,
            decode(b.sq_pessoa,null,'---',c.nome||' (desde '||to_char(b.inicio,'dd/mm/yy')||')') titular1,
            decode(d.sq_pessoa,null,'---',e.nome||' (desde '||to_char(d.inicio,'dd/mm/yy')||')') substituto1,
            b.sq_pessoa titular2, b.inicio inicio_titular,
            c.nome nm_titular, c.nome_resumido nm_resumido_titular,
            d.sq_pessoa substituto2, d.inicio inicio_substituto,
            e.nome nm_substituto, e.nome_resumido no_resumido_substituto,
            k.email email_substituto,
            j.email email_titular,
            n.nome tit_sala, n.telefone tit_tel1, n.telefone2 tit_tel2, n.ramal tit_ramal, n.fax tit_fax,
            h.nome sub_sala, h.telefone sub_tel1, h.telefone2 sub_tel2, h.ramal sub_ramal, h.fax sub_fax,
            o.logradouro tit_logradouro, i.logradouro sub_logradouro
       from eo_unidade                           a,
            eo_unidade_resp    b,
            co_pessoa          c,
            sg_autenticacao    j,
            eo_localizacao     n,
            co_pessoa_endereco o,
            eo_unidade_resp    d,
            co_pessoa          e,
            sg_autenticacao    k,
            eo_localizacao     h,
            co_pessoa_endereco i
      where (a.sq_unidade         = b.sq_unidade (+) and
             b.tipo_respons (+)   = 'T' and
             b.fim (+) is null
            )
        and (b.sq_pessoa          = c.sq_pessoa (+))
        and (c.sq_pessoa          = j.sq_pessoa (+))
        and (j.sq_localizacao     = n.sq_localizacao (+))
        and (n.sq_pessoa_endereco = o.sq_pessoa_endereco (+))
        and (a.sq_unidade         = d.sq_unidade (+) and
             d.tipo_respons (+)   = 'S' and
             d.fim (+) is null
            )
        and (d.sq_pessoa          = e.sq_pessoa (+))
        and (e.sq_pessoa          = k.sq_pessoa (+))
        and (k.sq_localizacao     = h.sq_localizacao (+))
        and (h.sq_pessoa_endereco = i.sq_pessoa_endereco (+))
        and a.sq_unidade  = p_chave;
end SP_GetUorgResp;
/

