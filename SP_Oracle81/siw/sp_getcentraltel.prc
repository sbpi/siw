create or replace procedure SP_GetCentralTel
   (p_chave              in number   default null,
    p_cliente            in number   default null,
    p_sq_pessoa_endereco in number   default null,
    p_sq_pessoa_telefone in number   default null,
    p_restricao          in varchar2 default null,
    p_result             out siw.sys_refcursor) is
begin
   -- Recupera os tipos de Tabela

   if p_restricao = 'TRONCO' then
     open p_result for
     select a.sq_central_fone, a.arquivo_bilhetes arquivo, a.recupera_bilhetes recupera, a.cliente, a.sq_pessoa_endereco,
            c.logradouro, c.bairro,
            d.nome nm_cidade, d.sq_cidade,
            e.co_uf uf,
            f.sq_tronco chave, f.codigo codigo, f.ativo,
            g.ddd, g.numero num_tel, g.padrao num_tel_padrao, g.sq_pessoa_telefone,
            h.nome nm_tipo, h.sq_tipo_telefone
       from tt_central                   a,
         co_pessoa            b,
         co_pessoa_endereco   c,
           co_cidade          d,
             co_uf            e,
         tt_tronco            f,
           co_pessoa_telefone g,
             co_tipo_telefone h
     where (a.cliente            = b.sq_pessoa)
       and (a.sq_pessoa_endereco = c.sq_pessoa_endereco)
       and (c.sq_cidade          = d.sq_cidade)
       and (d.co_uf              = e.co_uf)
       and (a.sq_central_fone    = f.sq_central_fone)
       and (f.sq_pessoa_telefone = g.sq_pessoa_telefone)
       and (g.sq_tipo_telefone   = h.sq_tipo_telefone)
       and ((p_chave              is null) or (p_chave              is not null and a.sq_central_fone    = p_chave))
       and ((p_cliente            is null) or (p_cliente            is not null and a.cliente            = p_cliente))
       and ((p_sq_pessoa_endereco is null) or (p_sq_pessoa_endereco is not null and a.sq_pessoa_endereco = p_sq_pessoa_endereco))
       and ((p_sq_pessoa_telefone is null) or (p_sq_pessoa_telefone is not null and f.sq_pessoa_telefone = p_sq_pessoa_telefone));

   else if p_restricao = 'USER' then
     open p_result for
     select a.sq_central_fone, a.arquivo_bilhetes arquivo, a.recupera_bilhetes recupera, a.cliente, a.sq_pessoa_endereco,
            b.sq_usuario_central, b.cliente, b.usuario, b.codigo,
            c.nome nm_usuario, c.nome_resumido nm_usuario_res, c.sq_pessoa chave, c.nome_indice
       from tt_central        a,
       tt_usuario b,
         co_pessoa c
     where (a.sq_central_fone = b.sq_central_fone)
       and (b.usuario         = c.sq_pessoa)
       and ((p_chave              is null) or (p_chave              is not null and a.sq_central_fone    = p_chave))
       and ((p_cliente            is null) or (p_cliente            is not null and a.cliente            = p_cliente))
       and ((p_sq_pessoa_endereco is null) or (p_sq_pessoa_endereco is not null and a.sq_pessoa_endereco = p_sq_pessoa_endereco));
   else
     open p_result for
     select a.sq_central_fone chave, a.arquivo_bilhetes arquivo, a.recupera_bilhetes recupera, a.cliente, a.sq_pessoa_endereco,
            c.logradouro, c.bairro,
            d.nome nm_cidade, d.sq_cidade,
            e.co_uf uf
     from tt_central                      a,
          co_pessoa            b,
            co_pessoa_endereco c,
              co_cidade        d,
                co_uf          e
     where (a.cliente            = b.sq_pessoa)
       and (a.sq_pessoa_endereco = c.sq_pessoa_endereco)
       and (c.sq_cidade          = d.sq_cidade)
       and (d.co_uf              = e.co_uf)
       and ((p_chave              is null) or (p_chave              is not null and a.sq_central_fone    = p_chave))
       and ((p_cliente            is null) or (p_cliente            is not null and a.cliente            = p_cliente))
       and ((p_sq_pessoa_endereco is null) or (p_sq_pessoa_endereco is not null and a.sq_pessoa_endereco = p_sq_pessoa_endereco));
    End If;
    End If;
end SP_getCentralTel;
/
