create or replace procedure SP_GetCaixa
   (p_chave       in  number   default null,
    p_cliente     in  number,
    p_usuario     in  number,
    p_unidade     in  number   default null,
    p_numero      in  number   default null,
    p_assunto     in  varchar2 default null,
    p_unid_autua  in  number   default null,
    p_nu_guia     in  number   default null,
    p_ano_guia    in  number   default null,
    p_ini         in date      default null,
    p_fim         in date      default null,
    p_restricao   in  varchar2 default null,
    p_result      out sys_refcursor
   ) is
begin
   If p_restricao is null or p_restricao = 'PACAIXA' or p_restricao = 'PREPARA' or p_restricao = 'TRAMITE' or p_restricao = 'RELPATRANS' or p_restricao = 'PADARQ' or p_restricao = 'CENTRAL' Then
      -- Recupera os grupos da caixa
      open p_result for 
         select a.sq_caixa, a.sq_unidade, a.sq_arquivo_local, a.assunto, a.descricao, 
                a.data_limite, a.numero, a.intermediario, a.destinacao_final, a.arquivo_data, a.arquivo_guia_numero, a.arquivo_guia_ano, 
                a.elimin_data, a.elimin_guia_numero, a.elimin_guia_ano,
                case when a.arquivo_guia_numero is not null then 'Arq.Central'
                     when a.elimin_guia_numero  is not null then 'Eliminado'
                     else 'Arq.Setorial'
                end as nm_situacao,
                b.nome as nm_unidade, b.sigla as sg_unidade,
                coalesce(c.qtd,0) as qtd
           from pa_caixa              a 
                inner join eo_unidade b on (a.sq_unidade = b.sq_unidade)
                left  join (select sq_caixa, count(sq_siw_solicitacao) as qtd
                              from pa_documento
                            group by sq_caixa
                           )          c on (a.sq_caixa   = c.sq_caixa)
          where a.cliente    = p_cliente
            and (p_chave     is null or (p_chave     is not null and a.sq_caixa         = p_chave  ))
            and (p_unidade   is null or (p_unidade   is not null and a.sq_unidade       = p_unidade))
            and (p_numero    is null or (p_numero    is not null and a.sq_caixa         = p_numero ))
            and (p_assunto   is null or (p_assunto   is not null and acentos(a.assunto) like '%' || acentos(p_assunto) || '%' ))
            and (p_ini       is null or (p_ini       is not null and (a.intermediario   between p_ini and p_fim or a.data_limite between p_ini and p_fim)))
            and (coalesce(p_restricao,'null') not in ('PACAIXA','PREPARA','TRAMITE','RELPATRANS','PADARQ','CENTRAL') or
                 (p_restricao = 'PACAIXA' and (a.sq_unidade in (select sq_unidade from sg_autenticacao where sq_pessoa = p_usuario
                                                                UNION
                                                                select sq_unidade from eo_unidade_resp where sq_pessoa = p_usuario and fim is null
                                                                UNION
                                                                select sq_unidade_lotacao from gp_contrato_colaborador where sq_pessoa = p_usuario and fim is null
                                                                 UNION
                                                                select sq_unidade_exercicio from gp_contrato_colaborador where sq_pessoa = p_usuario and fim is null
                                                               )
                                               or 0 < (select count(*) from sg_autenticacao where gestor_sistema = 'S' and sq_pessoa = p_usuario)
                                               or 0 < (select count(*) from sg_pessoa_modulo x join siw_modulo y on (x.sq_modulo = y.sq_modulo and y.sigla = 'PA') where x.sq_pessoa = p_usuario)
                                              )
                 ) or
                 (p_restricao = 'PREPARA' and a.arquivo_data is null) or
                 (p_restricao = 'TRAMITE' and a.arquivo_data is null and c.qtd > 0) or
                 (p_restricao = 'RELPATRANS' and a.arquivo_guia_numero is not null and a.arquivo_data is null) or
                 (p_restricao = 'PADARQ' and a.arquivo_guia_numero is not null and a.arquivo_data is not null and a.sq_arquivo_local is null) or
                 (p_restricao = 'CENTRAL' and a.sq_arquivo_local is not null)
                );
   Elsif p_restricao = 'PASTA' Then
      -- Recupera o conteúdo da caixa
      open p_result for 
         select a.sq_caixa, a.sq_unidade, a.sq_arquivo_local, a.assunto, a.descricao, 
                a.data_limite, a.numero, a.intermediario, a.destinacao_final, a.arquivo_data, a.arquivo_guia_numero, a.arquivo_guia_ano, 
                a.elimin_data, a.elimin_guia_numero, a.elimin_guia_ano,
                b.nome as nm_unidade, b.sigla as sg_unidade,
                coalesce(b3.sq_unidade, b.sq_unidade) as sq_unid_dest,
                coalesce(b3.nome, b.nome) as nm_unid_dest,
                coalesce(b3.sigla, b.sigla) as sg_unid_dest,
                c.numero_original,    c.numero_documento,                c.interno,   c.pasta,
                case c.processo when 'S' then 'Proc' else 'Doc' end as nm_tipo,
                case c.processo when 'S' then c.data_autuacao else d.inicio end as dt_limite,
                c.prefixo||'.'||substr(1000000+c.numero_documento,2,6)||'/'||c.ano||'-'||substr(100+c.digito,2,2) as protocolo,
                a.arquivo_guia_numero||'/'||a.arquivo_guia_ano||'-'||coalesce(b3.sigla,b.sigla) as guia_transferencia, 
                case when c.pessoa_origem is null then d1.sq_unidade else c1.sq_pessoa end as sq_origem,
                case when c.pessoa_origem is null then d1.nome else c1.nome end as nm_origem,
                case when c.pessoa_origem is null then d1.sigla else c1.nome_resumido end as nm_origem_resumido,
                c5.codigo as cd_assunto, c5.descricao as ds_assunto, 
                c5.fase_corrente_anos, c5.fase_intermed_anos, c5.fase_final_anos,
                c7.nome as nm_especie,   c7.sigla as sg_natureza,    c7.ativo as st_natureza,
                ca.sigla as sg_final,    ca.descricao as ds_final,
                d.descricao as detalhamento_assunto,
                d.inicio, retornaLimiteProtocolo(d.sq_siw_solicitacao) as prazo_guarda,
                d1.nome as nm_unid_origem, d1.sigla as sg_unid_origem,
                case d2.ativo 
                     when 'S' then 'CORRENTE'
                     else case d2.sigla
                               when 'AS' then 'ARQ.SETORIAL'
                               when 'AT' then 'ARQ.CENTRAL'
                          end
                end as localizacao
           from pa_caixa                                  a
                inner       join eo_unidade               b  on (a.sq_unidade                = b.sq_unidade)
                  inner     join pa_unidade               b1 on (b.sq_unidade                = b1.sq_unidade)
                    left    join pa_unidade               b2 on (b1.sq_unidade_pai           = b2.sq_unidade)
                      left  join eo_unidade               b3 on (b2.sq_unidade               = b3.sq_unidade)
                inner       join pa_documento             c  on (a.sq_caixa                  = c.sq_caixa)
                  left      join co_pessoa                c1 on (c.pessoa_origem            = c1.sq_pessoa)
                  inner     join pa_documento_assunto     c4 on (c.sq_siw_solicitacao       = c4.sq_siw_solicitacao and
                                                                 c4.principal               = 'S'
                                                                )
                    inner   join pa_assunto               c5 on (c4.sq_assunto              = c5.sq_assunto)
                      inner join pa_tipo_guarda           c6 on (c5.fase_corrente_guarda    = c6.sq_tipo_guarda)
                      inner join pa_tipo_guarda           c8 on (c5.fase_intermed_guarda    = c8.sq_tipo_guarda)
                      inner join pa_tipo_guarda           c9 on (c5.fase_final_guarda       = c9.sq_tipo_guarda)
                      inner join pa_tipo_guarda           ca on (c5.destinacao_final        = ca.sq_tipo_guarda)
                  inner     join pa_especie_documento     c7 on (c.sq_especie_documento     = c7.sq_especie_documento)
                  inner     join siw_solicitacao          d on (c.sq_siw_solicitacao        = d.sq_siw_solicitacao)
                    left    join eo_unidade               d1 on (d.sq_unidade               = d1.sq_unidade)
                    inner   join siw_tramite              d2 on (d.sq_siw_tramite           = d2.sq_siw_tramite)
          where a.cliente     = p_cliente
            and (p_chave      is null or (p_chave      is not null and a.sq_caixa            = p_chave  ))
            and (p_unidade    is null or (p_unidade    is not null and a.sq_unidade          = p_unidade))
            and (p_nu_guia    is null or (p_nu_guia    is not null and a.arquivo_guia_numero = p_nu_guia and a.arquivo_guia_ano = p_ano_guia))
            --and (p_unid_autua is null or (p_unid_autua is not null and c.unidade_autuacao    = p_unid_autua))
            and (p_numero     is null or (p_numero     is not null and a.numero              = p_numero ))
            and (p_ini        is null or (p_ini        is not null and a.arquivo_data        between p_ini and p_fim))
            and (p_assunto    is null or (p_assunto    is not null and acentos(a.assunto)    like '%' + acentos(p_assunto)+ '%' ));
   End If;
end SP_GetCaixa;
/
