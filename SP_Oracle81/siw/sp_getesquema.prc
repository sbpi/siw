create or replace procedure SP_GetEsquema
   (p_cliente   in  number,
    p_restricao in  varchar2 default null,
    p_chave     in  number   default null,
    p_modulo    in  number   default null,
    p_nome      in  varchar2 default null,
    p_tipo      in  varchar2 default null,
    p_formato   in  varchar2 default null,
    p_dt_ini    in  date     default null,
    p_dt_fim    in date      default null,
    p_ref_ini   in date      default null,
    p_ref_fim   in date      default null,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera os esquemas de importação/exportação
   If p_restricao is null Then
      open p_result for
         select a.sq_esquema, a.cliente, a.nome, a.descricao, a.tipo, a.ativo, a.formato,
                a.ws_servidor, a.ws_url, a.ws_acao, a.ws_mensagem, a.no_raiz,
                decode(a.tipo    ,'I','Importação','Exportação' ) nm_tipo,
                decode(a.formato ,'A','Arquivo'   ,'Web service') nm_formato,
                decode(a.ativo   ,'S','Sim'       ,'Não') nm_ativo,
                b.sq_modulo, b.nome nm_modulo, b.sigla sg_modulo,
                c.qtd_tabela,
                e.sq_ocorrencia, e.data_ocorrencia, e.data_referencia, e.processados, e.rejeitados,
                e.arquivo_processamento, e.arquivo_rejeicao,
                f.sq_pessoa, f.nome nm_pessoa, f.nome_resumido nm_pessoa_resumido,
                g.nome nm_recebido, g.tamanho tm_recebido, g.tipo tp_recebido, g.caminho cm_recebido, g.sq_siw_arquivo chave_recebido,
                h.nome nm_result,   h.tamanho tm_result,   h.tipo tp_result,   h.caminho cm_result  , h.sq_siw_arquivo chave_result
           from dc_esquema                        a,
                siw_modulo    b,
                (select sq_esquema, count(*) qtd_tabela
                                       from dc_esquema_tabela
                                     group by sq_esquema
                                    )             c,
                (select sq_esquema, max(sq_ocorrencia) sq_ocorrencia
                                       from dc_ocorrencia
                                      group by sq_esquema
                                    )             d,
                  dc_ocorrencia e,
                  co_pessoa     f,
                  siw_arquivo   g,
                  siw_arquivo   h
          where (a.sq_modulo     = b.sq_modulo)
            and (a.sq_esquema    = c.sq_esquema (+))
            and (a.sq_esquema    = d.sq_esquema (+))
            and (d.sq_ocorrencia = e.sq_ocorrencia (+))
            and (e.sq_pessoa     = f.sq_pessoa (+))
            and (e.arquivo_processamento = g.sq_siw_arquivo (+))
            and (e.arquivo_rejeicao      = h.sq_siw_arquivo (+))
            and a.cliente = p_cliente
            and ((p_chave    is null) or (p_chave   is not null and a.sq_esquema  = p_chave))
            and ((p_modulo   is null) or (p_modulo  is not null and a.sq_modulo   = p_modulo))
            and ((p_nome     is null) or (p_nome    is not null and upper(a.nome) like '%'||upper(p_nome)||'%'))
            and ((p_tipo     is null) or (p_tipo    is not null and a.tipo          = p_tipo))
            and ((p_formato  is null) or (p_formato is not null and formato       = p_formato))
            and ((p_dt_ini   is null) or (p_dt_ini  is not null and e.data_ocorrencia between p_dt_ini and p_dt_fim+1))
            and ((p_ref_ini  is null) or (p_ref_ini is not null and e.data_referencia between p_ref_ini and p_ref_fim+1));
   End If;
end SP_GetEsquema;
/
