create or replace procedure SP_GetAddressData
   (p_chave       in  number,
    p_result     out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados do endereco informado
   open p_result for
      select a.sq_pessoa,
             initcap(c.nome) endereco,
             f.sq_pais, f.co_uf, f.sq_cidade, b.logradouro,
             b.cep,b.padrao,b.bairro,b.complemento,
             b.sq_tipo_endereco, b.sq_pessoa_endereco, a.nome pessoa
      from co_pessoa          a,
           co_pessoa_endereco b,
              co_cidade f, 
              co_uf     e,
              co_pais   d,
           co_tipo_endereco   c
      where (b.sq_cidade = f.sq_cidade (+))
        and (f.co_uf     = e.co_uf (+) and
             f.sq_pais   = e.sq_pais (+)
            )
        and (f.sq_pais   = d.sq_pais (+))
        and a.sq_pessoa          = b.sq_pessoa
        and b.sq_tipo_endereco   = c.sq_tipo_endereco
        and b.sq_pessoa_endereco = p_chave;
end SP_GetaddressData;
/

