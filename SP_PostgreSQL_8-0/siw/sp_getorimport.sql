create or replace FUNCTION SP_GetOrImport
   (p_chave            numeric,
    p_cliente          numeric,
    p_responsavel     varchar,
    p_dt_ini          date,
    p_dt_fim          date,
    p_imp_ini         date,
    p_imp_fim         date,
    p_result          REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as importações dos dados financeiros
   open p_result for 
       select a.sq_orimporta chave, a.data, a.data_arquivo, a.registros, a.importados, a.rejeitados, a.situacao,
              a.sq_pessoa, a.arquivo_recebido, a.arquivo_registro,
              b.nome nm_recebido, b.tamanho tm_recebido, b.tipo tp_recebido, b.caminho cm_recebido, b.sq_siw_arquivo chave_recebido,
              c.nome nm_result,   c.tamanho tm_result,   c.tipo tp_result,   c.caminho cm_result,   c.sq_siw_arquivo chave_result,
              d.nome nm_resp,     d.nome_resumido nm_resumido_resp,
              to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_arquivo,
              case situacao
                when 0 then 'Completa'
                else        'Parcial'
              end nm_situacao
         from or_importacao          a
              inner join siw_arquivo b on (a.arquivo_recebido = b.sq_siw_arquivo)
              inner join siw_arquivo c on (a.arquivo_registro = c.sq_siw_arquivo)
              inner join co_pessoa   d on (a.sq_pessoa        = d.sq_pessoa)
        where a.cliente      = p_cliente
         and ((p_chave       is null) or (p_chave       is not null and a.sq_orimporta  = p_chave))
         and ((p_responsavel is null) or (p_responsavel is not null and acentos(d.nome) like '%'||acentos(p_responsavel)||'%'))
         and ((p_dt_ini      is null) or (p_dt_ini      is not null and a.data_arquivo  between p_dt_ini  and p_dt_fim+1))
         and ((p_imp_ini     is null) or (p_imp_ini     is not null and a.data          between p_imp_ini and p_imp_fim+1));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;