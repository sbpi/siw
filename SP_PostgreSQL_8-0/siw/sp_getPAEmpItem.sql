create or replace FUNCTION sp_getPAEmpItem
   (p_chave         numeric,
    P_solicitacao   numeric,
    p_atraso        varchar,
    p_ini           date,
    p_fim           date,
    p_restricao     varchar,
    p_result        REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera materiais e serviços
      open p_result for 
         select a.protocolo as chave, a.sq_siw_solicitacao,          a.devolucao,
                b.inicio,             b.fim,                         b.descricao,
                d.numero_original,    d.numero_documento,            d.ano,
                d.prefixo,            d.digito,                      d.interno,
                d.prefixo||'.'||substr(cast(1000000+d.numero_documento as varchar),2,6)||'/'||d.ano||'-'||substr(cast(100+d.digito as varchar),2,2) as protocolo,
                d.sq_especie_documento, d.sq_natureza_documento,     d.unidade_autuacao,
                d.data_autuacao,      d.pessoa_origem,               d.processo,
                d.circular,           d.copias,                      d.volumes,
                d.data_recebimento,   d.unidade_int_posse,           d.pessoa_ext_posse,
                d.tipo_juntada,       d.sq_caixa,                    d.pasta,
                d.data_setorial,      d.data_central,
                case tipo_juntada when 'A' then 'Anexado' when 'P' then 'Apensado' end as nm_tipo_juntada,
                to_char(d.data_juntada, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_juntada,
                to_char(d.data_desapensacao,'DD/MM/YYYY, HH24:MI:SS') as phpdt_desapensacao,
                case d.processo when 'S' then 'Proc' else 'Doc' end as nm_tipo,
                case when d.pessoa_origem is null then b3.nome else d2.nome end as nm_origem_doc,
                d1.nome as nm_especie,
                d6.numero as nr_caixa, montaNomeArquivoLocal(d6.sq_arquivo_local) as nm_arquivo_local,
                d7.sigla as sg_unid_caixa,
                d8.sq_siw_solicitacao as sq_eliminacao, d8.fim as dt_eliminacao, d8.sigla as sg_tramite_eliminacao
           from pa_emprestimo_item                        a
                inner       join siw_solicitacao          b  on (a.sq_siw_solicitacao       = b.sq_siw_solicitacao)
                  inner     join eo_unidade               b3 on (b.sq_unidade               = b3.sq_unidade)
                inner       join pa_documento             d  on (a.protocolo                = d.sq_siw_solicitacao)
                  inner     join pa_especie_documento     d1 on (d.sq_especie_documento     = d1.sq_especie_documento)
                  left      join co_pessoa                d2 on (d.pessoa_origem            = d2.sq_pessoa)
                    left    join co_tipo_pessoa           d3 on (d2.sq_tipo_pessoa          = d3.sq_tipo_pessoa)
                  inner     join pa_documento_assunto     d4 on (d.sq_siw_solicitacao       = d4.sq_siw_solicitacao and
                                                                 d4.principal               = 'S'
                                                                )
                    inner   join pa_assunto               d5 on (d4.sq_assunto              = d5.sq_assunto)
                  left      join pa_caixa                 d6 on (d.sq_caixa                 = d6.sq_caixa)
                    left    join eo_unidade               d7 on (d6.sq_unidade              = d7.sq_unidade)
                    left    join (select y.protocolo, y.sq_siw_solicitacao, x.fim, z.sigla, x.conclusao
                                    from siw_solicitacao          x
                                         inner join pa_eliminacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         inner join siw_tramite   z on (x.sq_siw_tramite     = z.sq_siw_tramite and z.sigla <> 'CA')
                                 )                        d8 on (d.sq_siw_solicitacao       = d8.protocolo)
          where (p_chave         is null or (p_chave         is not null and a.protocolo           = p_chave))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_atraso        is null or (p_atraso        is not null and a.devolucao           is null and b.fim < trunc(now())))
            and (p_ini           is null or (p_ini           is not null and a.devolucao           between p_ini and p_fim));
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;