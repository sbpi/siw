create or replace procedure SP_GetPDImportacao
   (p_chave           in  number  default null,
    p_cliente         in  number,
    p_responsavel     in varchar2 default null,
    p_dt_ini          in date     default null,
    p_dt_fim          in date     default null,
    p_imp_ini         in date     default null,
    p_imp_fim         in date     default null,
    p_result          out sys_refcursor) is
begin
   -- Recupera as importações dos dados financeiros
   open p_result for 
       select a.sq_arquivo_eletronico as chave, a.data_importacao, a.data_arquivo, a.registros, a.importados, a.rejeitados, 
              a.sq_pessoa, a.arquivo_recebido, a.arquivo_registro,
              b.nome nm_recebido, b.tamanho tm_recebido, b.tipo tp_recebido, b.caminho cm_recebido, b.sq_siw_arquivo chave_recebido,
              c.nome nm_result,   c.tamanho tm_result,   c.tipo tp_result,   c.caminho cm_result,   c.sq_siw_arquivo chave_result,
              d.nome nm_resp,     d.nome_resumido nm_resumido_resp,
              to_char(a.data_importacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_importacao,
              to_char(a.data_arquivo, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_arquivo,
              case rejeitados
                when 0 then 'Completa'
                else        'Parcial'
              end nm_situacao
         from pd_arquivo_eletronico  a
              inner join siw_arquivo b on (a.arquivo_recebido = b.sq_siw_arquivo)
              inner join siw_arquivo c on (a.arquivo_registro = c.sq_siw_arquivo)
              inner join co_pessoa   d on (a.sq_pessoa        = d.sq_pessoa)
        where a.cliente      = p_cliente
         and ((p_chave       is null) or (p_chave       is not null and a.sq_arquivo_eletronico = p_chave))
         and ((p_responsavel is null) or (p_responsavel is not null and acentos(d.nome)         like '%'||acentos(p_responsavel)||'%'))
         and ((p_dt_ini      is null) or (p_dt_ini      is not null and a.data_arquivo          between p_dt_ini  and p_dt_fim+1))
         and ((p_imp_ini     is null) or (p_imp_ini     is not null and a.data_importacao       between p_imp_ini and p_imp_fim+1));
end SP_GetPDImportacao;
/
