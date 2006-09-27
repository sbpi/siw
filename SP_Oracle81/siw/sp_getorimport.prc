create or replace procedure SP_GetOrImport
   (p_chave           in  number  default null,
    p_cliente         in  number,
    p_responsavel     in varchar2 default null,
    p_dt_ini          in date     default null,
    p_dt_fim          in date     default null,
    p_imp_ini         in date     default null,
    p_imp_fim         in date     default null,
    p_result          out siw.sys_refcursor) is
begin
   -- Recupera as importações dos dados financeiros
   open p_result for
       select a.sq_orimporta chave, a.data, a.data_arquivo, a.registros, a.importados, a.rejeitados, a.situacao,
              a.sq_pessoa, a.arquivo_recebido, a.arquivo_registro,
              b.nome nm_recebido, b.tamanho tm_recebido, b.tipo tp_recebido, b.caminho cm_recebido, b.sq_siw_arquivo chave_recebido,
              c.nome nm_result,   c.tamanho tm_result,   c.tipo tp_result,   c.caminho cm_result,   c.sq_siw_arquivo chave_result,
              d.nome nm_resp,     d.nome_resumido nm_resumido_resp,
              to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_arquivo,              
              decode(situacao,0,'Completa','Parcial') nm_situacao
         from or_importacao          a,
              siw_arquivo b,
              siw_arquivo c,
              co_pessoa   d
        where (a.arquivo_recebido = b.sq_siw_arquivo)
          and (a.arquivo_registro = c.sq_siw_arquivo)
          and (a.sq_pessoa        = d.sq_pessoa)
          and a.cliente      = p_cliente
          and ((p_chave       is null) or (p_chave       is not null and a.sq_orimporta  = p_chave))
          and ((p_responsavel is null) or (p_responsavel is not null and acentos(d.nome) like '%'||acentos(p_responsavel)||'%'))
          and ((p_dt_ini      is null) or (p_dt_ini      is not null and a.data_arquivo  between p_dt_ini  and p_dt_fim+1))
          and ((p_imp_ini     is null) or (p_imp_ini     is not null and a.data          between p_imp_ini and p_imp_fim+1));
end SP_GetOrImport;
/
