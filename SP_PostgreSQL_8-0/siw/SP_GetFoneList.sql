CREATE OR REPLACE FUNCTION siw.SP_GetFoneList
   (p_cliente   numeric,
    p_chave     numeric,
    p_restricao varchar,
    p_tipo_fone numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   If p_restricao is null Then
      -- Recupera todos os telefones, independente do tipo
      open p_result for
         select a.sq_pessoa,
                b.sq_pessoa_telefone, b.numero,b.sq_pessoa_telefone, b.ddd, b.padrao,
                c.sq_tipo_telefone, InitCap(c.nome) as tipo_telefone,
                d.nome as pais, e.co_uf, f.nome as cidade
           from siw.co_pessoa                             a
                inner         join siw.co_pessoa_telefone b on (a.sq_pessoa        = b.sq_pessoa)
                   left outer join siw.co_cidade          f on (b.sq_cidade        = f.sq_cidade)
                   left outer join siw.co_uf              e on (f.co_uf            = e.co_uf and
                                                            f.sq_pais          = e.sq_pais)
                   left outer join siw.co_pais            d on (e.sq_pais          = d.sq_pais)
                   inner      join siw.co_tipo_telefone   c on (b.sq_tipo_telefone = c.sq_tipo_telefone)
          where a.sq_pessoa        = p_cliente
         order by c.nome, b.numero;
   Elsif p_restricao = 'TRONCO' Then
      -- Recupera os telefones do cliente que ainda não foram vinculados a uma central telefônica
      open p_result for
         select a.sq_pessoa,
                b.sq_pessoa_telefone, b.numero, b.ddd, b.padrao,
                c.sq_tipo_telefone, InitCap(c.nome) as tipo_telefone,
                d.nome as pais, e.co_uf, f.nome as cidade, c.nome as nm_tipo
           from siw.co_pessoa                             a
                inner         join siw.co_pessoa_telefone b on (a.sq_pessoa          = b.sq_pessoa)
                   left outer join siw.co_cidade          f on (b.sq_cidade          = f.sq_cidade)
                   left outer join siw.co_uf              e on (f.co_uf              = e.co_uf)
                   left outer join siw.co_pais            d on (e.sq_pais            = d.sq_pais)
                   inner      join siw.co_tipo_telefone   c on (b.sq_tipo_telefone   = c.sq_tipo_telefone)
          where a.sq_pessoa        = p_cliente;
         --MINUS
         select a.sq_pessoa,
                b.sq_pessoa_telefone, b.numero, b.ddd, b.padrao,
                c.sq_tipo_telefone, InitCap(c.nome) as tipo_telefone,
                d.nome as pais, e.co_uf, f.nome as cidade, c.nome as nm_tipo
           from siw.co_pessoa                             a
                inner         join siw.co_pessoa_telefone b on (a.sq_pessoa          = b.sq_pessoa)
                   left outer join siw.co_cidade          f on (b.sq_cidade          = f.sq_cidade)
                   left outer join siw.co_uf              e on (f.co_uf              = e.co_uf)
                   left outer join siw.co_pais            d on (e.sq_pais            = d.sq_pais)
                   inner      join siw.co_tipo_telefone   c on (b.sq_tipo_telefone   = c.sq_tipo_telefone)
                   inner      join siw.tt_tronco          g on (b.sq_pessoa          = g.cliente and
                                                            b.sq_pessoa_telefone = g.sq_pessoa_telefone)
          where a.sq_pessoa        = p_cliente
         order by nm_tipo, numero;
   ElsIf p_restricao = 'TELEFONE' Then
      -- Recupera todos os telefones, independente do tipo
      open p_result for
         select a.sq_pessoa,
                b.sq_pessoa_telefone, b.numero,b.sq_pessoa_telefone, b.ddd, b.padrao,
                c.sq_tipo_telefone, InitCap(c.nome) as tipo_telefone,
                d.nome as pais, e.co_uf, f.nome as cidade
           from siw.co_pessoa                             a
                inner         join siw.co_pessoa_telefone b on (a.sq_pessoa        = b.sq_pessoa)
                   left outer join siw.co_cidade          f on (b.sq_cidade        = f.sq_cidade)
                   left outer join siw.co_uf              e on (f.co_uf            = e.co_uf)
                   left outer join siw.co_pais            d on (e.sq_pais          = d.sq_pais)
                   inner      join siw.co_tipo_telefone   c on (b.sq_tipo_telefone = c.sq_tipo_telefone)
          where a.sq_pessoa        = p_cliente
            and b.padrao           = 'S'
            and (p_chave     is null or (p_chave     is not null and b.sq_pessoa_telefone <> p_chave))
            and (p_tipo_fone is null or (p_tipo_fone is not null and c.sq_tipo_telefone   = p_tipo_fone))
         order by c.nome, b.numero;
   End If;
   return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetFoneList
   (p_cliente   numeric,
    p_chave     numeric,
    p_restricao varchar,
    p_tipo_fone numeric) OWNER TO siw;
