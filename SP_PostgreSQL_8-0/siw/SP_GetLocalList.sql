create or replace function SP_GetLocalList
   (p_cliente   numeric,
    p_chave     numeric,
    p_restricao varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   If p_restricao is null Then
      -- Recupera as localizações do cliente
      open p_result for 
         select a.sq_localizacao,c.logradouro||' - '||a.nome||' ('||b.sigla||')' as localizacao,
                b.sq_unidade, b.sq_unidade_pai
           from eo_localizacao a, eo_unidade b, co_pessoa_endereco c
          where a.sq_unidade         = b.sq_unidade
            and b.sq_pessoa_endereco = c.sq_pessoa_endereco
            and c.sq_pessoa          = p_cliente
          order by c.logradouro, a.nome, b.sigla;
   End If;
   return p_result;
end; $$ language 'plpgsql' volatile;


