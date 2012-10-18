create or replace procedure SP_GetSolicApoioList
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
  If p_restricao is null Then
    -- Recupera os dados ou a lista de apoios de um projeto
    open p_result for 
      select a.sq_solic_apoio, a.sq_siw_solicitacao, a.sq_tipo_apoio, a.entidade, a.sq_pessoa_atualizacao,
             a.ultima_atualizacao, a.descricao, a.valor, b.nome nm_tipo_apoio, b.sigla sg_tipo_apoio,
             c.nome_resumido
        from siw_solic_apoio              a
             inner join siw_tipo_apoio    b on (a.sq_tipo_apoio         = b.sq_tipo_apoio)
             inner join co_pessoa         c on (a.sq_pessoa_atualizacao = c.sq_pessoa)
       where (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
         and (p_chave_aux is null or (p_chave_aux is not null and a.sq_solic_apoio     = p_chave_aux));
  Elsif p_restricao = 'CRONOGRAMA' Then
    open p_result for 
      select a.sq_solic_apoio,        a.sq_siw_solicitacao,  a.sq_tipo_apoio,            a.entidade,          a.sq_pessoa_atualizacao,
             a.ultima_atualizacao,    a.descricao,           a.valor,
             b.nome nm_tipo_apoio,    b.sigla sg_tipo_apoio,
             c.nome_resumido,
             d.valor_previsto vl_fonte_prev,                 d.valor_real vl_fonte_real,
             e.sq_rubrica_cronograma, e.inicio,              e.fim,                      e.valor_previsto,   e.valor_real,
             e.quantidade,
             f.sq_projeto_rubrica,    f.codigo,              f.nome
        from siw_solic_apoio                      a
             inner     join siw_tipo_apoio        b on (a.sq_tipo_apoio         = b.sq_tipo_apoio)
             inner     join co_pessoa             c on (a.sq_pessoa_atualizacao = c.sq_pessoa)
             inner     join pj_cronograma_apoio   d on (a.sq_solic_apoio        = d.sq_solic_apoio)
               inner   join pj_rubrica_cronograma e on (d.sq_rubrica_cronograma = e.sq_rubrica_cronograma)
                 inner join pj_rubrica            f on (e.sq_projeto_rubrica    = f.sq_projeto_rubrica)
       where (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
         and (p_chave_aux is null or (p_chave_aux is not null and a.sq_solic_apoio     = p_chave_aux));
  End If;
End SP_GetSolicApoioList;
/
