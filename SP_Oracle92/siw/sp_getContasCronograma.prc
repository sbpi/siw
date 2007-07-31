create or replace procedure SP_GetContasCronograma
   (p_chave             in number   default null,
    p_siw_solicitacao   in number   default null,
    p_prestacao_contas  in number   default null,
    p_inicio            in date     default null,
    p_fim               in date     default null,
    p_limite            in date     default null,
    p_tipo              in varchar2 default null,
    p_restricao         in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os cronogramas de prestação de contas
   If p_restricao is null Then
      open p_result for     
         select a.sq_contas_cronograma as chave, a.sq_siw_solicitacao, a.sq_prestacao_contas, a.inicio, a.fim, a.limite,
                a.sq_pessoa_atualizacao, a.ultima_atualizacao,
                b.tipo, b.nome nm_prestacao_contas,
                case b.tipo when 'P' then 'Parcial' else 'Final' end as nm_tipo,
                d.nome, c.inicio as solic_ini, c.fim as solic_fim,
                e.titulo,
                f.nome_resumido
           from siw_contas_cronograma            a
                inner   join ac_prestacao_contas b on (a.sq_prestacao_contas = b.sq_prestacao_contas)
                inner   join siw_solicitacao     c on (a.sq_siw_solicitacao  = c.sq_siw_solicitacao)
                  left  join pj_projeto          e on (c.sq_siw_solicitacao  = e.sq_siw_solicitacao)
                  inner join siw_menu            d on (c.sq_menu             = d.sq_menu)
                left    join co_pessoa           f on (a.sq_pessoa_atualizacao = f.sq_pessoa)
          where ((p_chave             is null) or (p_chave             is not null and a.sq_contas_cronograma = p_chave))
            and ((p_siw_solicitacao   is null) or (p_siw_solicitacao   is not null and a.sq_siw_solicitacao   = p_siw_solicitacao))
            and ((p_prestacao_contas  is null) or (p_prestacao_contas  is not null and a.sq_prestacao_contas  = p_prestacao_contas))
            and ((p_tipo              is null) or (p_tipo              is not null and b.tipo                 = p_tipo))
            and ((p_inicio            is null) or (p_inicio            is not null and a.inicio between p_inicio and p_fim));
   Elsif p_restricao = 'EXISTE' Then
      open p_result for
         select a.sq_contas_cronograma
           from siw_contas_cronograma          a
                inner join ac_prestacao_contas b on (a.sq_prestacao_contas = b.sq_prestacao_contas)
          where a.sq_siw_solicitacao   =  p_siw_solicitacao
            and ((p_chave is null) or (p_chave is not null and a.sq_contas_cronograma <> p_chave))
            and ((p_tipo  is null) or (p_tipo  is not null and b.tipo                  = p_tipo));
   Elsif p_restricao = 'PRESTACAO' Then
      open p_result for     
         select a.sq_contas_cronograma as chave, a.sq_siw_solicitacao, a.sq_prestacao_contas, a.inicio, a.fim, a.limite,
                b.tipo, b.nome nm_prestacao_contas,
                case b.tipo when 'P' then 'Parcial' else 'Final' end as nm_tipo
           from siw_contas_cronograma          a
                inner join ac_prestacao_contas b on (a.sq_prestacao_contas = b.sq_prestacao_contas)
          where a.sq_siw_solicitacao = p_siw_solicitacao;
   End If;
end SP_GetContasCronograma;
/
