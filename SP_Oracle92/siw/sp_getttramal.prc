create or replace procedure SP_GetTTRamal
   (p_cliente         in number,
    p_chave           in  number   default null,
    p_sq_central_fone in  number   default null,
    p_codigo          in  varchar2 default null,
    p_restricao       in  varchar2 default null,
    p_result          out sys_refcursor) is
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
     from Tt_Ramal                            a
          inner       join tt_ramal_usuario   g on (a.sq_ramal           = g.sq_ramal)
            inner     join tt_usuario         h on (g.sq_usuario_central = h.sq_usuario_central)
              inner   join co_pessoa          i on (h.usuario            = i.sq_pessoa)    
          inner       join tt_central         c on (a.sq_central_fone    = c.sq_central_fone)
            inner     join co_pessoa_endereco d on (d.sq_pessoa_endereco = c.sq_pessoa_endereco)
              inner   join co_cidade          e on (d.sq_cidade          = e.sq_cidade)
                inner join co_uf              f on (e.co_uf              = f.co_uf)     
     where c.cliente           = p_cliente
       and ((p_chave           is null) or (p_chave           is not null and a.sq_ramal        = p_chave))
       and ((p_sq_central_fone is null) or (p_sq_central_fone is not null and a.sq_central_fone = p_sq_central_fone))
       and ((p_codigo          is null) or (p_codigo          is not null and a.codigo          = p_codigo));   
   else
     open   p_result for 
     select a.sq_ramal chave, a.codigo, a.sq_central_fone,
            d.logradouro,
            e.nome nm_cidade,
            f.co_uf uf           
     from Tt_Ramal                            a
          inner       join tt_central         c on (a.sq_central_fone    = c.sq_central_fone)
            inner     join co_pessoa_endereco d on (d.sq_pessoa_endereco = c.sq_pessoa_endereco)
              inner   join co_cidade          e on (d.sq_cidade          = e.sq_cidade)
                inner join co_uf              f on (e.co_uf              = f.co_uf)     
     where c.cliente           = p_cliente
       and ((p_chave           is null) or (p_chave           is not null and a.sq_ramal        = p_chave))
       and ((p_sq_central_fone is null) or (p_sq_central_fone is not null and a.sq_central_fone = p_sq_central_fone))
       and ((p_codigo          is null) or (p_codigo          is not null and a.codigo          = p_codigo));   
   End If;
end SP_GetTTRamal;
/
