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
    p_result      out siw.sys_refcursor) is
begin
   -- Recupera as ações do PPA
   If p_sq_siw_solicitacao is null Then
      open p_result for
         select a.sq_acao_ppa chave, a.sq_acao_ppa_pai, a.cliente, a.codigo, a.nome,
                a.responsavel, a.telefone, a.email, a.ativo, a.padrao,
                a.selecionada_mpog, a.selecionada_relevante,
                a.aprovado, a.saldo, a.empenhado, a.liquidado, a.liquidar,
                decode(a.ativo,'S','Sim','Não') nm_ativo,
                decode(a.padrao,'S','Sim','Não') nm_padrao,
                b.nome nm_acao_pai, b.codigo cd_pai,
                Nvl(c.acao,0) acao, Nvl(d.outras_acao,0) outras_acao,
                e.sq_siw_solicitacao,
                decode(b.sq_acao_ppa,null,a.nome,b.nome||a.nome) ordena
           from or_acao_ppa                 a,
                or_acao_ppa b,
                (select x.sq_acao_ppa,count(*) acao
                                   from or_acao x
                                 group by x.sq_acao_ppa
                                )           c,
                (select y.sq_acao_ppa,count(*) outras_acao
                                   from or_acao_financ y
                                 group by y.sq_acao_ppa
                                )           d,
                or_acao     e
          where (a.sq_acao_ppa_pai = b.sq_acao_ppa (+))
            and (a.sq_acao_ppa     = c.sq_acao_ppa (+))
            and (a.sq_acao_ppa     = d.sq_acao_ppa (+))
            and (a.sq_acao_ppa     = e.sq_acao_ppa (+))
            and a.cliente        = p_cliente
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
                decode(a.ativo,'S','Sim','Não') nm_ativo,
                decode(a.padrao,'S','Sim','Não') nm_padrao,
                b.nome nm_acao_pai, b.codigo cd_pai,
                Nvl(c.acao,0) acao, Nvl(d.outras_acao,0) outras_acao,
                decode(b.sq_acao_ppa,null,a.nome,b.nome||a.nome) ordena
           from or_acao_ppa                 a,
                or_acao_ppa b,
                (select x.sq_acao_ppa,count(*) acao
                                   from or_acao x
                                  where x.sq_siw_solicitacao = p_sq_siw_solicitacao
                                 group by x.sq_acao_ppa
                                )           c,
                (select y.sq_acao_ppa,count(*) outras_acao
                                   from or_acao_financ y
                                  where y.sq_siw_solicitacao = p_sq_siw_solicitacao
                                 group by y.sq_acao_ppa
                                )           d,
                 or_acao     e
          where (a.sq_acao_ppa_pai = b.sq_acao_ppa (+))
            and (a.sq_acao_ppa     = c.sq_acao_ppa (+))
            and (a.sq_acao_ppa     = d.sq_acao_ppa (+))
            and (a.sq_acao_ppa     = e.sq_acao_ppa (+))
            and a.cliente            = p_cliente
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

