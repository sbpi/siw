create or replace procedure SP_GetTTRamal
   (p_chave           in  number   default null,
    p_sq_central_fone in  number   default null,
    p_codigo          in  varchar2 default null,
    p_restricao       in  varchar2 default null,
    p_result          out siw.sys_refcursor) is
begin
   -- Recupera os tipos de Tabela
   if p_restricao = 'USER' then
     open   p_result for
     select a.sq_ramal, a.codigo, a.sq_central_fone,
            d.logradouro,
            e.nome nm_cidade,
            f.co_uf uf,
            g.inicio, g.fim, g.sq_usuario_central chave,
            nvl(g.fim,sysdate) dt_fim,
            h.usuario,
            i.nome nm_usuario
     from Tt_Ramal                     a,
          tt_ramal_usuario     g,
          tt_usuario         h,
          co_pessoa        i,
          tt_central           c,
          co_pessoa_endereco d,
          co_cidade        e,
          co_uf          f
     where (a.sq_ramal           = g.sq_ramal)
       and (g.sq_usuario_central = h.sq_usuario_central)
       and (h.usuario            = i.sq_pessoa)
       and (a.sq_central_fone    = c.sq_central_fone)
       and (d.sq_pessoa_endereco = c.sq_pessoa_endereco)
       and (d.sq_cidade          = e.sq_cidade)
       and (e.co_uf              = f.co_uf) 
       and ((p_chave           is null) or (p_chave           is not null and a.sq_ramal        = p_chave))
       and ((p_sq_central_fone is null) or (p_sq_central_fone is not null and a.sq_central_fone = p_sq_central_fone))
       and ((p_codigo          is null) or (p_codigo          is not null and a.codigo          = p_codigo));
   else
     open   p_result for
     select a.sq_ramal chave, a.codigo, a.sq_central_fone,
            d.logradouro,
            e.nome nm_cidade,
            f.co_uf uf
     from Tt_Ramal                     a,
          tt_central           c,
          co_pessoa_endereco d,
          co_cidade        e,
          co_uf          f
     where (a.sq_central_fone    = c.sq_central_fone)
       and (d.sq_pessoa_endereco = c.sq_pessoa_endereco)
       and (d.sq_cidade          = e.sq_cidade)
       and (e.co_uf              = f.co_uf)
       and ((p_chave           is null) or (p_chave           is not null and a.sq_ramal        = p_chave))
       and ((p_sq_central_fone is null) or (p_sq_central_fone is not null and a.sq_central_fone = p_sq_central_fone))
       and ((p_codigo          is null) or (p_codigo          is not null and a.codigo          = p_codigo));
   End If;
end SP_GetTTRamal;
/

