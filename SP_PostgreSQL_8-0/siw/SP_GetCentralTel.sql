CREATE OR REPLACE FUNCTION siw.SP_GetCentralTel
   (p_chave              numeric,
    p_cliente            numeric,
    p_sq_pessoa_endereco numeric,
    p_sq_pessoa_telefone numeric,
    p_restricao          varchar)
  RETURNS character varying AS
$BODY$
DECLARE
    p_result             refcursor;
begin
   -- Recupera os tipos de Tabela
   
   if p_restricao = 'TRONCO' then
     open p_result for 
     select a.sq_central_fone, a.arquivo_bilhetes as arquivo, a.recupera_bilhetes as recupera, a.cliente, a.sq_pessoa_endereco,
            c.logradouro, c.bairro,
            d.nome as nm_cidade, d.sq_cidade,
            e.co_uf as uf,
            f.sq_tronco as chave, f.codigo as codigo, f.ativo,
            g.ddd, g.numero as num_tel, g.padrao as num_tel_padrao, g.sq_pessoa_telefone,
            h.nome as nm_tipo, h.sq_tipo_telefone
       from siw.tt_central                   a
         inner join siw.co_pessoa            b on (a.cliente            = b.sq_pessoa)
         inner join siw.co_pessoa_endereco   c on (a.sq_pessoa_endereco = c.sq_pessoa_endereco)
           inner join siw.co_cidade          d on (c.sq_cidade          = d.sq_cidade)
             inner join siw.co_uf            e on (d.co_uf              = e.co_uf)
         inner join siw.tt_tronco            f on (a.sq_central_fone    = f.sq_central_fone)
           inner join siw.co_pessoa_telefone g on (f.sq_pessoa_telefone = g.sq_pessoa_telefone)
             inner join siw.co_tipo_telefone h on (g.sq_tipo_telefone   = h.sq_tipo_telefone)
     where a.cliente              = p_cliente
       and ((p_chave              is null) or (p_chave              is not null and a.sq_central_fone    = p_chave))
       and ((p_sq_pessoa_endereco is null) or (p_sq_pessoa_endereco is not null and a.sq_pessoa_endereco = p_sq_pessoa_endereco))
       and ((p_sq_pessoa_telefone is null) or (p_sq_pessoa_telefone is not null and f.sq_pessoa_telefone = p_sq_pessoa_telefone));
       
   elsif p_restricao = 'USER' then
     open p_result for 
     select a.sq_central_fone, a.arquivo_bilhetes as arquivo, a.recupera_bilhetes as recupera, a.cliente, a.sq_pessoa_endereco,
            b.sq_usuario_central, b.cliente, b.usuario, b.codigo,
            c.nome as nm_usuario, c.nome_resumido as nm_usuario_res, c.sq_pessoa as chave, c.nome_indice
       from siw.tt_central        a
       inner join  siw.tt_usuario b on (a.sq_central_fone = b.sq_central_fone)
         inner join siw.co_pessoa c on (b.usuario         = c.sq_pessoa)
     where a.cliente              = p_cliente
       and ((p_chave              is null) or (p_chave              is not null and a.sq_central_fone    = p_chave))
       and ((p_sq_pessoa_endereco is null) or (p_sq_pessoa_endereco is not null and a.sq_pessoa_endereco = p_sq_pessoa_endereco));
   elsif p_restricao = 'CLASSIF' Then
      open p_result for 
      select a.sq_cc, a.sigla, a.nome, e.nome as nm_pai,
             e.nome||' - '||a.nome as nm_cc,
             coalesce(b.sq_central_fone,0) as existe, 
             coalesce(d.sq_central_fone,0) as marcado
        from siw.ct_cc            a
             inner join siw.ct_cc e on (a.sq_cc_pai = e.sq_cc)
             left  join siw.tt_cc b on (a.sq_cc     = b.sq_cc)
             left  join siw.tt_cc d on (a.sq_cc     = d.sq_cc and
                                    d.desativacao is null
                                   )
             left  join (select x.sq_cc, count(y.sq_cc) as filhos
                           from siw.ct_cc           x
                                left join siw.ct_cc y on (x.sq_cc = y.sq_cc_pai)
                          group by x.sq_cc
                        )     c on (a.sq_cc     = c.sq_cc)
       where a.cliente = p_cliente
         and a.ativo   = 'S'
         and c.filhos  = 0
         and b.sq_cc   = p_chave;
   else  
     open p_result for 
     select a.sq_central_fone as chave, a.arquivo_bilhetes as arquivo, a.recupera_bilhetes as recupera, a.cliente, a.sq_pessoa_endereco,
            c.logradouro, c.bairro,
            d.nome as nm_cidade, d.sq_cidade,
            e.co_uf as uf
     from siw.tt_central                      a
          inner join siw.co_pessoa            b on (a.cliente            = b.sq_pessoa)
            inner join siw.co_pessoa_endereco c on (a.sq_pessoa_endereco = c.sq_pessoa_endereco)
              inner join siw.co_cidade        d on (c.sq_cidade          = d.sq_cidade)
                inner join siw.co_uf          e on (d.co_uf              = e.co_uf)
     where a.cliente              = p_cliente
       and ((p_chave              is null) or (p_chave              is not null and a.sq_central_fone    = p_chave))
       and ((p_sq_pessoa_endereco is null) or (p_sq_pessoa_endereco is not null and a.sq_pessoa_endereco = p_sq_pessoa_endereco));
    End If;
end 
 $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCentralTel
   (p_chave              numeric,
    p_cliente            numeric,
    p_sq_pessoa_endereco numeric,
    p_sq_pessoa_telefone numeric,
    p_restricao          varchar) OWNER TO siw;
