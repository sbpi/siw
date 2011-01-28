create or replace FUNCTION SP_GetPDImportacao
   (p_chave            numeric,
    p_cliente          numeric,
    p_responsavel     varchar,
    p_dt_ini          date,
    p_dt_fim          date,
    p_imp_ini         date,
    p_imp_fim         date,
    p_restricao       varchar,
    p_result          REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as importações dos dados financeiros
   open p_result for 
       select a.sq_arquivo_eletronico as chave, a.data_importacao, a.data_arquivo, a.registros, a.importados, a.rejeitados, 
              a.sq_pessoa, a.arquivo_recebido, a.arquivo_registro,
              case a.tipo when 0 then 'Bilhetes aéreos' when 1 then 'Hospedagem/Locação/Seguro' else null end as tp_fatura,
              b.nome nm_recebido, b.tamanho tm_recebido, b.tipo tp_recebido, b.caminho cm_recebido, b.sq_siw_arquivo chave_recebido,
              c.nome nm_result,   c.tamanho tm_result,   c.tipo tp_result,   c.caminho cm_result,   c.sq_siw_arquivo chave_result,
              d.nome nm_resp,     d.nome_resumido nm_resumido_resp,
              to_char(a.data_importacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_importacao,
              to_char(a.data_arquivo, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_arquivo,
              case rejeitados
                when 0 then 'Completa'
                else        'Parcial'
              end nm_situacao,
              coalesce(e.qtd,0) as qt_fatura
         from pd_arquivo_eletronico  a
              inner join siw_arquivo b on (a.arquivo_recebido      = b.sq_siw_arquivo)
              inner join siw_arquivo c on (a.arquivo_registro      = c.sq_siw_arquivo)
              inner join co_pessoa   d on (a.sq_pessoa             = d.sq_pessoa)
              left  join (select x.sq_arquivo_eletronico, x.tipo, count(*) as qtd
                            from pd_fatura_agencia x
                           group by x.sq_arquivo_eletronico, x.tipo
                         )           e on (a.sq_arquivo_eletronico = e.sq_arquivo_eletronico)
        where a.cliente      = p_cliente
         and ((p_chave       is null) or (p_chave       is not null and a.sq_arquivo_eletronico = p_chave))
         and ((p_responsavel is null) or (p_responsavel is not null and acentos(d.nome)         like '%'||acentos(p_responsavel)||'%'))
         and ((p_dt_ini      is null) or (p_dt_ini      is not null and a.data_arquivo          between p_dt_ini  and p_dt_fim+1))
         and ((p_imp_ini     is null) or (p_imp_ini     is not null and a.data_importacao       between p_imp_ini and p_imp_fim+1));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;