CREATE OR REPLACE FUNCTION siw.SP_GetAddressMenu
   (p_cliente   numeric,
    p_chave     numeric,
    p_restricao varchar)

    RETURNS character varying AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera as localizações do cliente
   open p_result for
      select a.sq_pessoa_endereco,
             a.logradouro||' ('||case c.co_uf when 'EX' then b.nome||'-'||d.nome else b.nome||'-'||c.co_uf end ||')' as endereco,
             (select count(*) from siw.siw_menu_endereco where sq_pessoa_endereco = a.sq_pessoa_endereco and sq_menu = p_chave) as checked
      from siw.co_pessoa_endereco a, siw.co_tipo_endereco a1, siw.co_cidade b, siw.co_uf c, siw.co_pais d
      where a.sq_cidade        = b.sq_cidade
        and b.co_uf            = c.co_uf
        and b.sq_pais          = c.sq_pais
        and b.sq_pais          = d.sq_pais
        and a.sq_tipo_endereco = a1.sq_tipo_endereco
        and a1.internet        = 'N'
        and a1.email           = 'N'
        and a.sq_pessoa        = p_cliente
      order by acentos(a.logradouro);
end $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetAddressMenu(p_cliente   numeric,p_chave     numeric,p_restricao varchar) OWNER TO siw;
