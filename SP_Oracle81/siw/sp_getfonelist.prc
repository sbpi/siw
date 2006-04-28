create or replace procedure SP_GetFoneList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_tipo_fone in number   default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera todos os telefones, independente do tipo
      open p_result for
         select a.sq_pessoa,
                b.sq_pessoa_telefone, b.numero,b.sq_pessoa_telefone, b.ddd, b.padrao,
                c.sq_tipo_telefone, InitCap(c.nome) tipo_telefone,
                d.nome pais, e.co_uf, f.nome cidade
           from co_pessoa                             a,
                co_pessoa_telefone b,
                   co_cidade          f,
                   co_uf              e,
                   co_pais            d,
                   co_tipo_telefone   c
          where (a.sq_pessoa        = b.sq_pessoa)
            and (b.sq_cidade        = f.sq_cidade (+))
            and (f.co_uf            = e.co_uf (+))
            and (e.sq_pais          = d.sq_pais (+))
            and (b.sq_tipo_telefone = c.sq_tipo_telefone)
            and a.sq_pessoa        = p_cliente
         order by c.nome, b.numero;
   Elsif p_restricao = 'TRONCO' Then
      -- Recupera os telefones do cliente que ainda não foram vinculados a uma central telefônica
      open p_result for
         select a.sq_pessoa,
                b.sq_pessoa_telefone, b.numero, b.ddd, b.padrao,
                c.sq_tipo_telefone, InitCap(c.nome) tipo_telefone,
                d.nome pais, e.co_uf, f.nome cidade, c.nome nm_tipo
           from co_pessoa                             a,
                co_pessoa_telefone b,
                   co_cidade          f,
                   co_uf              e,
                   co_pais            d,
                   co_tipo_telefone   c
          where (a.sq_pessoa          = b.sq_pessoa)
            and (b.sq_cidade          = f.sq_cidade (+))
            and (f.co_uf              = e.co_uf (+))
            and (e.sq_pais            = d.sq_pais (+))
            and (b.sq_tipo_telefone   = c.sq_tipo_telefone)
            and a.sq_pessoa        = p_cliente
         MINUS
         select a.sq_pessoa,
                b.sq_pessoa_telefone, b.numero, b.ddd, b.padrao,
                c.sq_tipo_telefone, InitCap(c.nome) tipo_telefone,
                d.nome pais, e.co_uf, f.nome cidade, c.nome nm_tipo
           from co_pessoa                             a,
                co_pessoa_telefone b,
                   co_cidade          f,
                   co_uf              e,
                   co_pais            d,
                   co_tipo_telefone   c,
                   tt_tronco          g
          where (a.sq_pessoa          = b.sq_pessoa)
            and (b.sq_cidade          = f.sq_cidade (+))
            and (f.co_uf              = e.co_uf (+))
            and (e.sq_pais            = d.sq_pais (+))
            and (b.sq_tipo_telefone   = c.sq_tipo_telefone)
            and (b.sq_pessoa          = g.cliente 
                 and b.sq_pessoa_telefone = g.sq_pessoa_telefone
                 )
            and a.sq_pessoa        = p_cliente
         order by nm_tipo, numero;
   ElsIf p_restricao = 'TELEFONE' Then
      -- Recupera todos os telefones, independente do tipo
      open p_result for
         select a.sq_pessoa,
                b.sq_pessoa_telefone, b.numero,b.sq_pessoa_telefone, b.ddd, b.padrao,
                c.sq_tipo_telefone, InitCap(c.nome) tipo_telefone,
                d.nome pais, e.co_uf, f.nome cidade
           from co_pessoa                             a,
                co_pessoa_telefone b,
                   co_cidade          f,
                   co_uf              e,
                   co_pais            d,
                   co_tipo_telefone   c
          where (a.sq_pessoa        = b.sq_pessoa)
            and (b.sq_cidade        = f.sq_cidade (+))
            and (f.co_uf            = e.co_uf (+))
            and (e.sq_pais          = d.sq_pais (+))
            and (b.sq_tipo_telefone = c.sq_tipo_telefone)
            and a.sq_pessoa        = p_cliente
            and b.padrao           = 'S'
            and (p_chave     is null or (p_chave     is not null and b.sq_pessoa_telefone <> p_chave))
            and (p_tipo_fone is null or (p_tipo_fone is not null and c.sq_tipo_telefone   = p_tipo_fone))            
         order by c.nome, b.numero;   
   End If;
end SP_GetFoneList;
/
