create or replace procedure SP_GetLocalList
   (p_cliente   in number,
    p_chave     in number,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as localizações do cliente
      open p_result for 
         select a.sq_localizacao,c.logradouro||' - '||a.nome||' ('||b.sigla||')' localizacao,
                b.sq_unidade, b.sq_unidade_pai
           from eo_localizacao                a
                inner join eo_unidade         b on a.sq_unidade         = b.sq_unidade
                inner join co_pessoa_endereco c on b.sq_pessoa_endereco = c.sq_pessoa_endereco
          where c.sq_pessoa = p_cliente
            and (p_chave    is null or (p_chave is not null and b.sq_unidade = p_chave))
          order by c.logradouro, a.nome, b.sigla;
   End If;
end SP_GetLocalList;
/
