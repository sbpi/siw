create or replace procedure SP_GetAcaoPPA
   (p_chave               in  number    default null,
    p_cliente             in  number,
    p_programa            in  number    default null,
    p_acao                in  number    default null,
    p_responsavel         in  varchar2  default null,
    p_mpog                in  varchar2  default null,
    p_relevante           in  varchar2  default null,
    p_sq_siw_solicitacao  in  number    default null,
    p_cod_programa        in  varchar2  default null,
    p_cod_acao            in  varchar2  default null,
    p_restricao           in  varchar2  default null,
    p_result      out sys_refcursor) is
begin
   -- Recupera as a��es do PPA
   If p_restricao = 'CADASTRO' Then
      open p_result for
         select a.sq_acao_ppa chave, a.sq_acao_ppa_pai, a.cliente, a.codigo, a.nome,
                a.responsavel, a.telefone, a.email, a.ativo, a.padrao,
                a.selecionada_mpog, a.selecionada_relevante,
                a.aprovado, a.saldo, a.empenhado, a.liquidado, a.liquidar,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case a.padrao when 'S' then 'Sim' else 'N�o' end nm_padrao,
                b.nome nm_acao_pai, b.codigo cd_pai,
                c.acao acao, d.outras_acao outras_acao,
                e.sq_siw_solicitacao,
                case when b.sq_acao_ppa is null
                     then a.nome
                     else b.nome||a.nome
                end ordena
           from or_acao_ppa                 a
                left outer join or_acao_ppa b on (a.sq_acao_ppa_pai = b.sq_acao_ppa)
                left outer join (select x.sq_acao_ppa,count(*) acao
                                   from or_acao x
                                 group by x.sq_acao_ppa
                                )           c on (a.sq_acao_ppa     = c.sq_acao_ppa)
                left outer join (select y.sq_acao_ppa,count(*) outras_acao
                                   from or_acao_financ y
                                 group by y.sq_acao_ppa
                                )           d on (a.sq_acao_ppa     = d.sq_acao_ppa)
                left outer join or_acao     e on (a.sq_acao_ppa     = e.sq_acao_ppa)
          where a.cliente        = p_cliente
            and a.sq_acao_ppa_pai is null
            and ((p_sq_siw_solicitacao is null) or (p_sq_siw_solicitacao is not null and a.sq_acao_ppa <> p_sq_siw_solicitacao))
            and ((p_chave        is null) or (p_chave       is not null and a.sq_acao_ppa           = p_chave))
            and ((p_programa     is null) or (p_programa    is not null and a.sq_acao_ppa_pai       = p_programa or
                                                                            a.sq_acao_ppa           = p_programa))
            and ((p_acao         is null) or (p_acao        is not null and a.sq_acao_ppa           = p_acao))
            and ((p_responsavel  is null) or (p_responsavel is not null and acentos(a.responsavel)  like '%'||acentos(p_responsavel)||'%'))
            and ((p_mpog         is null) or (p_mpog        is not null and a.selecionada_mpog      = p_mpog))
            and ((p_relevante    is null) or (p_relevante   is not null and a.selecionada_relevante = p_relevante))
            and ((p_cod_programa is null) or (p_cod_programa is not null and b.codigo               = p_cod_programa))
            and ((p_cod_acao     is null) or (p_cod_acao     is not null and a.codigo               = p_cod_acao));
   ElsIf p_restricao = 'IDENTIFICACAO' or p_restricao = 'CONSULTA' Then
      open p_result for
         select a.sq_acao_ppa chave, a.sq_acao_ppa_pai, a.cliente, a.codigo, a.nome,
                a.responsavel, a.telefone, a.email, a.ativo, a.padrao,
                a.selecionada_mpog, a.selecionada_relevante,
                a.aprovado, a.saldo, a.empenhado, a.liquidado, a.liquidar,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case a.padrao when 'S' then 'Sim' else 'N�o' end nm_padrao,
                b.nome nm_acao_pai, b.codigo cd_pai,
                c.acao acao, d.outras_acao outras_acao,
                case when b.sq_acao_ppa is null
                     then a.nome
                     else b.nome||a.nome
                end ordena
           from or_acao_ppa                 a
                left outer join or_acao_ppa b on (a.sq_acao_ppa_pai = b.sq_acao_ppa)
                left outer join (select x.sq_acao_ppa,count(*) acao
                                   from or_acao x
                                  where 1=1
                                    and ((p_sq_siw_solicitacao is null) or (p_sq_siw_solicitacao is not null and x.sq_siw_solicitacao = p_sq_siw_solicitacao))
                                 group by x.sq_acao_ppa
                                )           c on (a.sq_acao_ppa     = c.sq_acao_ppa)
                left outer join (select y.sq_acao_ppa,count(*) outras_acao
                                   from or_acao_financ y
                                  where 1=1
                                    and ((p_sq_siw_solicitacao is null) or (p_sq_siw_solicitacao is not null and y.sq_siw_solicitacao = p_sq_siw_solicitacao))
                                 group by y.sq_acao_ppa
                                )           d on (a.sq_acao_ppa     = d.sq_acao_ppa)
                left outer join or_acao     e on (a.sq_acao_ppa     = e.sq_acao_ppa)
          where a.cliente            = p_cliente
            and a.sq_acao_ppa_pai    is not null
            and ((p_restricao = 'CONSULTA') or (p_restricao = 'IDENTIFICACAO' and c.acao           is null))
            and ((p_chave       is null) or (p_chave       is not null and a.sq_acao_ppa           = p_chave))
            and ((p_programa    is null) or (p_programa    is not null and a.sq_acao_ppa_pai       = p_programa or
                                                                           a.sq_acao_ppa           = p_programa))
            and ((p_acao        is null) or (p_acao        is not null and a.sq_acao_ppa           = p_acao))
            and ((p_responsavel is null) or (p_responsavel is not null and acentos(a.responsavel)  like '%'||acentos(p_responsavel)||'%'))
            and ((p_mpog        is null) or (p_mpog        is not null and a.selecionada_mpog      = p_mpog))
            and ((p_relevante   is null) or (p_relevante   is not null and a.selecionada_relevante = p_relevante));
   ElsIf p_restricao = 'FINANCIAMENTO' Then
      open p_result for
         select a.sq_acao_ppa chave, a.sq_acao_ppa_pai, a.cliente, a.codigo, a.nome,
                a.responsavel, a.telefone, a.email, a.ativo, a.padrao,
                a.selecionada_mpog, a.selecionada_relevante,
                a.aprovado, a.saldo, a.empenhado, a.liquidado, a.liquidar,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case a.padrao when 'S' then 'Sim' else 'N�o' end nm_padrao,
                b.nome nm_acao_pai, b.codigo cd_pai,
                c.acao acao, d.outras_acao outras_acao,
                case when b.sq_acao_ppa is null
                     then a.nome
                     else b.nome||a.nome
                end ordena
           from or_acao_ppa                 a
                left outer join or_acao_ppa b on (a.sq_acao_ppa_pai = b.sq_acao_ppa)
                left outer join (select x.sq_acao_ppa,count(*) acao
                                   from or_acao x
                                  where x.sq_siw_solicitacao = p_sq_siw_solicitacao
                                 group by x.sq_acao_ppa
                                )           c on (a.sq_acao_ppa     = c.sq_acao_ppa)
                left outer join (select y.sq_acao_ppa,count(*) outras_acao
                                   from or_acao_financ y
                                  where y.sq_siw_solicitacao = p_sq_siw_solicitacao
                                 group by y.sq_acao_ppa
                                )           d on (a.sq_acao_ppa     = d.sq_acao_ppa)
                left outer join or_acao     e on (a.sq_acao_ppa     = e.sq_acao_ppa)
          where a.cliente            = p_cliente
            and a.sq_acao_ppa_pai    is not null
            and outras_acao          is null
            and acao                 is null
            and ((p_chave       is null) or (p_chave       is not null and a.sq_acao_ppa           = p_chave))
            and ((p_programa    is null) or (p_programa    is not null and a.sq_acao_ppa_pai       = p_programa or
                                                                           a.sq_acao_ppa           = p_programa))
            and ((p_acao        is null) or (p_acao        is not null and a.sq_acao_ppa           = p_acao))
            and ((p_responsavel is null) or (p_responsavel is not null and acentos(a.responsavel)  like '%'||acentos(p_responsavel)||'%'))
            and ((p_mpog        is null) or (p_mpog        is not null and a.selecionada_mpog      = p_mpog))
            and ((p_relevante   is null) or (p_relevante   is not null and a.selecionada_relevante = p_relevante));
   ElsIf p_sq_siw_solicitacao is null Then
      open p_result for
         select a.sq_acao_ppa chave, a.sq_acao_ppa_pai, a.cliente, a.codigo, a.nome,
                a.responsavel, a.telefone, a.email, a.ativo, a.padrao,
                a.selecionada_mpog, a.selecionada_relevante,
                a.aprovado, a.saldo, a.empenhado, a.liquidado, a.liquidar,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case a.padrao when 'S' then 'Sim' else 'N�o' end nm_padrao,
                b.nome nm_acao_pai, b.codigo cd_pai,
                c.acao acao, d.outras_acao outras_acao,
                e.sq_siw_solicitacao,
                case when b.sq_acao_ppa is null
                     then a.nome
                     else b.nome||a.nome
                end ordena
           from or_acao_ppa                 a
                left outer join or_acao_ppa b on (a.sq_acao_ppa_pai = b.sq_acao_ppa)
                left outer join (select x.sq_acao_ppa,count(*) acao
                                   from or_acao x
                                 group by x.sq_acao_ppa
                                )           c on (a.sq_acao_ppa     = c.sq_acao_ppa)
                left outer join (select y.sq_acao_ppa,count(*) outras_acao
                                   from or_acao_financ y
                                 group by y.sq_acao_ppa
                                )           d on (a.sq_acao_ppa     = d.sq_acao_ppa)
                left outer join or_acao     e on (a.sq_acao_ppa     = e.sq_acao_ppa)
          where a.cliente        = p_cliente
            and ((p_chave        is null) or (p_chave       is not null and a.sq_acao_ppa           = p_chave))
            and ((p_programa     is null) or (p_programa    is not null and a.sq_acao_ppa_pai       = p_programa or
                                                                            a.sq_acao_ppa           = p_programa))
            and ((p_acao         is null) or (p_acao        is not null and a.sq_acao_ppa           = p_acao))
            and ((p_responsavel  is null) or (p_responsavel is not null and acentos(a.responsavel)  like '%'||acentos(p_responsavel)||'%'))
            and ((p_mpog         is null) or (p_mpog        is not null and a.selecionada_mpog      = p_mpog))
            and ((p_relevante    is null) or (p_relevante   is not null and a.selecionada_relevante = p_relevante))
            and ((p_cod_programa is null) or (p_cod_programa is not null and b.codigo               = p_cod_programa))
            and ((p_cod_acao     is null) or (p_cod_acao     is not null and a.codigo               = p_cod_acao));            
   Else
      open p_result for
         select a.sq_acao_ppa chave, a.sq_acao_ppa_pai, a.cliente, a.codigo, a.nome,
                a.responsavel, a.telefone, a.email, a.ativo, a.padrao,
                a.selecionada_mpog, a.selecionada_relevante,
                a.aprovado, a.saldo, a.empenhado, a.liquidado, a.liquidar,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case a.padrao when 'S' then 'Sim' else 'N�o' end nm_padrao,
                b.nome nm_acao_pai, b.codigo cd_pai,
                c.acao acao, d.outras_acao outras_acao,
                case when b.sq_acao_ppa is null
                     then a.nome
                     else b.nome||a.nome
                end ordena
           from or_acao_ppa                 a
                left outer join or_acao_ppa b on (a.sq_acao_ppa_pai = b.sq_acao_ppa)
                left outer join (select x.sq_acao_ppa,count(*) acao
                                   from or_acao x
                                  where x.sq_siw_solicitacao = p_sq_siw_solicitacao
                                 group by x.sq_acao_ppa
                                )           c on (a.sq_acao_ppa     = c.sq_acao_ppa)
                left outer join (select y.sq_acao_ppa,count(*) outras_acao
                                   from or_acao_financ y
                                  where y.sq_siw_solicitacao = p_sq_siw_solicitacao
                                 group by y.sq_acao_ppa
                                )           d on (a.sq_acao_ppa     = d.sq_acao_ppa)
                left outer join or_acao     e on (a.sq_acao_ppa     = e.sq_acao_ppa)
          where a.cliente            = p_cliente
            and ((p_chave       is null) or (p_chave       is not null and a.sq_acao_ppa           = p_chave))
            and ((p_programa    is null) or (p_programa    is not null and a.sq_acao_ppa_pai       = p_programa or
                                                                           a.sq_acao_ppa           = p_programa))
            and ((p_acao        is null) or (p_acao        is not null and a.sq_acao_ppa           = p_acao))
            and ((p_responsavel is null) or (p_responsavel is not null and acentos(a.responsavel)  like '%'||acentos(p_responsavel)||'%'))
            and ((p_mpog        is null) or (p_mpog        is not null and a.selecionada_mpog      = p_mpog))
            and ((p_relevante   is null) or (p_relevante   is not null and a.selecionada_relevante = p_relevante));
   End If;
end SP_GetAcaoPPA;
/
