create or replace FUNCTION SP_GetTTRamal
   (p_cliente         numeric,
    p_chave            numeric,
    p_sq_central_fone  numeric,
    p_codigo           varchar,
    p_restricao        varchar,
    p_result          REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de Tabela
   if p_restricao = 'USER' then
     open   p_result for 
     select a.sq_ramal, a.codigo, a.sq_central_fone,
            d.logradouro,
            e.nome nm_cidade,
            f.co_uf uf,
            g.inicio, g.fim, g.sq_usuario_central chave,
            nvl(g.fim,now()) dt_fim,
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
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;