create or replace function SP_GetSiwCliData
   (p_cnpj     varchar,
    p_result   refcursor
   ) returns refcursor as $$
begin
   -- Retorna os dados de um cliente do SIW a partir do CNPJ
   open p_result for
      select a.sq_pessoa, a.nome, a.nome_resumido, a.sq_tipo_vinculo, 
             b.cnpj, b.inscricao_estadual, b.sede, b.inicio_atividade,
             c.tamanho_min_senha, c.tamanho_max_senha, c.dias_vig_senha, 
             c.maximo_tentativas, c.dias_aviso_expir,
             d.sq_cidade, d.co_uf, d.sq_pais, 
             e.sq_agencia, e.sq_banco, 
             f.sq_segmento
      from co_pessoa          a left outer join co_pessoa_segmento f on (a.sq_pessoa = f.sq_pessoa),  
           co_pessoa_juridica b,  
           siw_cliente        c left outer join co_agencia e on (c.sq_agencia_padrao = e.sq_agencia),
           co_cidade          d 
      where a.sq_pessoa         = b.sq_pessoa 
        and a.sq_pessoa         = c.sq_pessoa 
        and c.sq_cidade_padrao  = d.sq_cidade 
        and b.cnpj              = p_cnpj;
   return p_result;
end; $$ language 'plpgsql' volatile;

