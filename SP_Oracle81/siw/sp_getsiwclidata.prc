create or replace procedure SP_GetSiwCliData
   (p_cnpj     in  varchar2,
    p_result   out siw.sys_refcursor
   ) is
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
      from co_pessoa          a,
           co_pessoa_segmento f,
           co_pessoa_juridica b,
           siw_cliente        c,
           co_cidade          d,
           co_agencia         e 
      where (a.sq_pessoa = f.sq_pessoa (+))
        and (c.sq_agencia_padrao = e.sq_agencia (+))
        and a.sq_pessoa         = b.sq_pessoa
        and a.sq_pessoa         = c.sq_pessoa
        and c.sq_cidade_padrao  = d.sq_cidade
        and b.cnpj              = p_cnpj;
end SP_GetSiwCliData;
/

