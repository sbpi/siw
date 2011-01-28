create or replace FUNCTION SP_GetCTEspecificacao
   (p_cliente      numeric,
    p_chave        numeric,
    p_chave_aux    numeric,
    p_ano          varchar,
    p_ativo        varchar,
    p_ultimo_nivel varchar,
    p_ctcc         numeric,
    p_restricao    varchar,
    p_result       REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os tipos de contrato do cliente
      open p_result for 
         select a.sq_especificacao_despesa chave, 
                case when b.nome is null then a.nome else b.nome||' - '||a.nome end nm_tipo,
                a.sq_cc, a.especificacao_pai chave_pai, a.ano, a.codigo, a.nome, a.valor, a.ultimo_nivel,
                a.ativo,
                c.nome nm_ct_cc
           from ct_especificacao_despesa               a
                   left      join ct_especificacao_despesa b on (a.especificacao_pai = b.sq_especificacao_despesa)
                     left    join ct_especificacao_despesa d on (b.especificacao_pai = d.sq_especificacao_despesa)
                       left  join ct_especificacao_despesa e on (d.especificacao_pai = e.sq_especificacao_despesa)
                   inner     join ct_cc                    c on (a.sq_cc             = c.sq_cc)
          where a.cliente         = p_cliente
            and (p_ano          is null or (p_ano          is not null and a.ano          = p_ano))
            and (p_ativo        is null or (p_ativo        is not null and a.ativo        = p_ativo))
            and (p_ultimo_nivel is null or (p_ultimo_nivel is not null and a.ultimo_nivel = p_ultimo_nivel))
            and (p_ctcc         is null or (p_ctcc         is not null and a.sq_cc        = p_ctcc))
         order by 2;
   Elsif p_restricao = 'HERANCA' Then
      -- Recupera os tipos de contrato do cliente
      open p_result for 
         select a.sq_especificacao_despesa chave, 
                case when b.nome is null then a.nome else b.nome||' - '||a.nome end nm_tipo,
                a.sq_cc, a.especificacao_pai chave_pai, a.ano, a.codigo, a.nome, a.valor, a.ultimo_nivel,
                a.ativo,
                c.nome nm_ct_cc
           from ct_especificacao_despesa               a
                   left  join ct_especificacao_despesa b on (a.especificacao_pai = b.sq_especificacao_despesa)
                   inner join ct_cc                    c on (a.sq_cc             = c.sq_cc)
          where a.cliente            = p_cliente
            and (p_ativo     is null or (p_ativo  is not null and a.ativo = p_ativo))
         order by 2;      
   Elsif p_restricao = 'ALTERA' Then
      -- Recupera os tipos de contrato do cliente
      open p_result for 
         select a.sq_especificacao_despesa chave, 
                a.sq_cc, a.especificacao_pai chave_pai, a.ano, a.codigo, a.nome, a.valor, a.ultimo_nivel,
                a.ativo
           from ct_especificacao_despesa a
          where a.sq_especificacao_despesa = p_chave
         order by 2;
   Elsif p_restricao = 'SUBORDINACAO' Then
      -- Recupera os tipos de contrato do cliente para seleção de subordinação
      open p_result for 
         select a.sq_especificacao_despesa chave, a.nome
           from ct_especificacao_despesa a
          where a.cliente            = p_cliente
            and (p_chave_aux    is null or (p_chave_aux    is not null and a.sq_especificacao_despesa <> p_chave_aux))
            and (p_ano          is null or (p_ano          is not null and a.ano          = p_ano))
            and (p_ativo        is null or (p_ativo        is not null and a.ativo        = p_ativo))
            and (p_ultimo_nivel is null or (p_ultimo_nivel is not null and a.ultimo_nivel = p_ultimo_nivel))
            and (p_ctcc         is null or (p_ctcc         is not null and a.sq_cc        = p_ctcc))
         order by 2;
   Elsif p_restricao = 'PAI' Then
      -- Recupera os tipos de contrato do cliente que não são subordinados a ninguém
      open p_result for
         select a.sq_especificacao_despesa chave, a.cliente, a.sq_cc, a.especificacao_pai chave_pai, a.ano, a.codigo, 
                a.nome, valor, a.ultimo_nivel, a.ativo, Nvl(b.Filho,0) Filho,
                c.nome nm_ct_cc
         from ct_especificacao_despesa a
                 left outer join (select especificacao_pai,count(*) Filho 
                                    from ct_especificacao_despesa x 
                                   where cliente = p_cliente 
                                  group by especificacao_pai) b 
                               on (a.sq_especificacao_despesa = b.especificacao_pai)
                 inner      join ct_cc c on (a.sq_cc          = c.sq_cc)
         where a.cliente               = p_cliente
           and a.especificacao_pai     is null
            and (p_ano       is null or (p_ano    is not null and a.ano   = p_ano))
            and (p_ativo     is null or (p_ativo  is not null and a.ativo = p_ativo))           
         order by a.nome;
   Elsif p_restricao = 'FILHO' Then
      -- Recupera os tipos de contrato do cliente, subordinados ao tipo informado
      open p_result for 
         select a.sq_especificacao_despesa chave, a.cliente, a.sq_cc, a.especificacao_pai chave_pai, a.ano, a.codigo, 
                a.nome, valor, a.ultimo_nivel, a.ativo, Nvl(b.Filho,0) Filho,
                c.nome nm_ct_cc
           from ct_especificacao_despesa a
                  left outer join (select especificacao_pai,count(*) Filho 
                                    from ct_especificacao_despesa x 
                                   where cliente = p_cliente 
                                  group by especificacao_pai) b 
                               on (a.sq_especificacao_despesa = b.especificacao_pai)
                   inner      join ct_cc c on (a.sq_cc          = c.sq_cc)
          where a.cliente               = p_cliente
            and a.especificacao_pai    = p_chave
            and (p_ano       is null or (p_ano    is not null and a.ano   = p_ano))
            and (p_ativo     is null or (p_ativo  is not null and a.ativo = p_ativo))           
         order by a.nome;
   Elsif p_restricao = 'ANOS' Then
      -- Recupera os anos existentes
      open p_result for 
        select distinct ano
          from ct_especificacao_despesa a
         where cliente = p_cliente;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;