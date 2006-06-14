create or replace procedure SP_GetCentralTel
   (p_chave              in number   default null,
    p_cliente            in number   default null,
    p_sq_pessoa_endereco in number   default null,
    p_sq_pessoa_telefone in number   default null,
    p_restricao          in varchar2 default null,
    p_result             out sys_refcursor) is
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
       from tt_central                   a
         inner join co_pessoa            b on (a.cliente            = b.sq_pessoa)
         inner join co_pessoa_endereco   c on (a.sq_pessoa_endereco = c.sq_pessoa_endereco)
           inner join co_cidade          d on (c.sq_cidade          = d.sq_cidade)
             inner join co_uf            e on (d.co_uf              = e.co_uf)
         inner join tt_tronco            f on (a.sq_central_fone    = f.sq_central_fone)
           inner join co_pessoa_telefone g on (f.sq_pessoa_telefone = g.sq_pessoa_telefone)
             inner join co_tipo_telefone h on (g.sq_tipo_telefone   = h.sq_tipo_telefone)
     where ((p_chave              is null) or (p_chave              is not null and a.sq_central_fone    = p_chave))
       and ((p_cliente            is null) or (p_cliente            is not null and a.cliente            = p_cliente))
       and ((p_sq_pessoa_endereco is null) or (p_sq_pessoa_endereco is not null and a.sq_pessoa_endereco = p_sq_pessoa_endereco))
       and ((p_sq_pessoa_telefone is null) or (p_sq_pessoa_telefone is not null and f.sq_pessoa_telefone = p_sq_pessoa_telefone));
       
   else if p_restricao = 'USER' then
     open p_result for 
     select a.sq_central_fone, a.arquivo_bilhetes arquivo, a.recupera_bilhetes recupera, a.cliente, a.sq_pessoa_endereco,
            b.sq_usuario_central, b.cliente, b.usuario, b.codigo,
            c.nome nm_usuario, c.nome_resumido nm_usuario_res, c.sq_pessoa chave, c.nome_indice
       from tt_central        a
       inner join  tt_usuario b on (a.sq_central_fone = b.sq_central_fone)
         inner join co_pessoa c on (b.usuario         = c.sq_pessoa)
     where ((p_chave              is null) or (p_chave              is not null and a.sq_central_fone    = p_chave))
       and ((p_cliente            is null) or (p_cliente            is not null and a.cliente            = p_cliente))
       and ((p_sq_pessoa_endereco is null) or (p_sq_pessoa_endereco is not null and a.sq_pessoa_endereco = p_sq_pessoa_endereco));
   else  
     open p_result for 
     select a.sq_central_fone chave, a.arquivo_bilhetes arquivo, a.recupera_bilhetes recupera, a.cliente, a.sq_pessoa_endereco,
            c.logradouro, c.bairro,
            d.nome nm_cidade, d.sq_cidade,
            e.co_uf uf
     from tt_central                      a
          inner join co_pessoa            b on (a.cliente            = b.sq_pessoa)
            inner join co_pessoa_endereco c on (a.sq_pessoa_endereco = c.sq_pessoa_endereco)
              inner join co_cidade        d on (c.sq_cidade          = d.sq_cidade)
                inner join co_uf          e on (d.co_uf              = e.co_uf)
     where ((p_chave              is null) or (p_chave              is not null and a.sq_central_fone    = p_chave))
       and ((p_cliente            is null) or (p_cliente            is not null and a.cliente            = p_cliente))
       and ((p_sq_pessoa_endereco is null) or (p_sq_pessoa_endereco is not null and a.sq_pessoa_endereco = p_sq_pessoa_endereco));
    End If;
    End If;
end SP_getCentralTel;
/
