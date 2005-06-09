create or replace procedure SP_GetCustomerData
   (p_cliente  in  number,
    p_result   out siw.sys_refcursor
   ) is
begin
   open p_result for 
      select a.*,
             b.co_uf, b.sq_pais, b.nome cidade,
             c.codigo, c.nome agencia,
             d.nome, d.nome_resumido, d.sq_tipo_vinculo,
             e.cnpj, e.inscricao_estadual, e.inicio_atividade, e.sede,
             g.nome pais,
             h.sq_segmento, h.nome segmento,
             i.sq_banco, i.nome banco
        from siw_cliente                     a,
             co_cidade          b,
             co_pais            g,
             co_agencia         c,
             co_banco           i,
             co_pessoa          d,
             co_pessoa_juridica e,
             co_pessoa_segmento f,
             co_segmento        h
       where a.sq_pessoa         = p_cliente
         and (a.sq_cidade_padrao  = b.sq_cidade)
         and (b.sq_pais           = g.sq_pais)
         and (a.sq_agencia_padrao = c.sq_agencia)
         and (c.sq_banco          = i.sq_banco)
         and (a.sq_pessoa         = d.sq_pessoa)
         and (a.sq_pessoa         = e.sq_pessoa)
         and (a.sq_pessoa         = f.sq_pessoa)
         and (f.sq_segmento       = h.sq_segmento);
end SP_GetCustomerData;
/

