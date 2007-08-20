create or replace procedure SP_GetAgreeType
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_cliente   in number,
    p_nome      in varchar2 default null,
    p_sigla     in varchar2 default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os tipos de contrato do cliente
      open p_result for 
         select a.sq_tipo_acordo,     case when b.nome is null then a.nome else b.nome||' - '||a.nome end nm_tipo,
                a.sq_tipo_acordo_pai, a.nome,            a.sigla, a.modalidade, 
                a.prazo_indeterm,     a.pessoa_fisica,   a.pessoa_juridica, a.ativo
           from ac_tipo_acordo a
                   inner join ac_tipo_acordo b on (a.sq_tipo_acordo_pai = b.sq_tipo_acordo)
          where a.ativo              = 'S' 
            and a.cliente            = p_cliente
         order by 2;
   ElsIf substr(p_restricao,1,2)='GC' Then
      -- Recupera os tipos de contrato do cliente
      open p_result for 
         select a.sq_tipo_acordo,     case when b.nome is null then a.nome else b.nome||' - '||a.nome end nm_tipo,
                a.sq_tipo_acordo_pai, a.nome,            a.sigla, a.modalidade, 
                a.prazo_indeterm,     a.pessoa_fisica,   a.pessoa_juridica, a.ativo
           from ac_tipo_acordo            a
                inner join ac_tipo_acordo b on (a.sq_tipo_acordo_pai = b.sq_tipo_acordo)
          where (p_chave       is null and 
                 a.ativo       = 'S' and 
                 a.cliente     = p_cliente and 
                 ((p_restricao = 'GCAGERAL' and a.modalidade = 'I') or
                  (p_restricao = 'GCBGERAL' and a.modalidade = 'E') or
                  (p_restricao = 'GCCGERAL' and a.modalidade = 'I') or
                  (p_restricao = 'GCDGERAL' and a.modalidade not in ('F','I')) or
                  (p_restricao = 'GCRGERAL' and a.modalidade = 'F') or
                  (p_restricao = 'GCPGERAL' and a.modalidade = 'I')
                 )
                )
             or (p_chave       is not null and a.sq_tipo_acordo = p_chave)
         order by 2;
   Elsif p_restricao = 'HERANCA' Then
      -- Recupera os tipos de contrato do cliente
      open p_result for 
         select a.sq_tipo_acordo,     case when b.nome is null then a.nome else b.nome||' - '||a.nome end nm_tipo,
                a.sq_tipo_acordo_pai, a.nome,            a.sigla, a.modalidade, 
                a.prazo_indeterm,     a.pessoa_fisica,   a.pessoa_juridica, a.ativo
           from ac_tipo_acordo a
                   left outer join ac_tipo_acordo b on (a.sq_tipo_acordo_pai = b.sq_tipo_acordo)
          where a.ativo              = 'S' 
            and a.cliente            = p_cliente
         order by 2;
   Elsif p_restricao = 'ALTERA' Then
      -- Recupera os tipos de contrato do cliente
      open p_result for 
         select a.sq_tipo_acordo,
                a.sq_tipo_acordo_pai, a.nome,            a.sigla, a.modalidade, 
                a.prazo_indeterm,     a.pessoa_fisica,   a.pessoa_juridica, a.ativo
           from ac_tipo_acordo a
          where a.sq_tipo_acordo = p_chave
         order by 2;
   Elsif upper(p_restricao) = 'SUBTODOS' Then
     -- Recupera os tipos aos quais o atual pode ser subordinado
      open p_result for
         select a.sq_tipo_acordo,a.nome as nm_tipo,
                montanomeTipoAcordo(a.sq_tipo_acordo) as nome_completo,
                coalesce(b.qtd,0) as qt_acordos
           from ac_tipo_acordo   a
                left  join (select x.sq_tipo_acordo, count(x.sq_siw_solicitacao) qtd 
                              from ac_acordo x
                            group by x.sq_tipo_acordo
                           )      b on (a.sq_tipo_acordo = b.sq_tipo_acordo)
          where a.cliente = p_cliente
            and 0         = coalesce(b.qtd,0)
         order by a.nome;
   Elsif upper(p_restricao) = 'SUBPARTE' Then
     -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
      open p_result for
         select a.sq_tipo_acordo,a.nome as nm_tipo,
                montanomeTipoAcordo(a.sq_tipo_acordo) as nome_completo,
                coalesce(b.qtd,0) as qt_acordos
           from ac_tipo_acordo   a
                left  join (select x.sq_tipo_acordo, count(x.sq_siw_solicitacao) qtd 
                              from ac_acordo x
                            group by x.sq_tipo_acordo
                           )      b on (a.sq_tipo_acordo = b.sq_tipo_acordo)
          where a.cliente = p_cliente
            and 0         = coalesce(b.qtd,0)
            and a.sq_tipo_acordo not in (select x.sq_tipo_acordo
                                           from ac_tipo_acordo x
                                          where x.cliente   = p_cliente
                                         start with x.sq_tipo_acordo = p_chave_aux
                                         connect by prior x.sq_tipo_acordo = x.sq_tipo_acordo_pai
                                        )
         order by a.nome;
   Elsif p_restricao = 'PAI' Then
      -- Recupera os tipos de contrato do cliente que não são subordinados a ninguém
      open p_result for 
         select a.sq_tipo_acordo, a.nome, a.modalidade, a.prazo_indeterm, a.ativo, a.pessoa_juridica, 
                 a.pessoa_fisica, a.sigla, Nvl(b.Filho,0) as Filho, coalesce(c.qtd,0) as qt_acordos
           from ac_tipo_acordo a
                left outer join (select sq_tipo_acordo_pai,count(*) Filho 
                                   from ac_tipo_acordo x 
                                  where cliente = p_cliente 
                                 group by sq_tipo_acordo_pai
                                ) b on (a.sq_tipo_acordo = b.sq_tipo_acordo_pai)
                left  join (select x.sq_tipo_acordo, count(x.sq_siw_solicitacao) qtd 
                              from ac_acordo x
                            group by x.sq_tipo_acordo
                           )      c on (a.sq_tipo_acordo = c.sq_tipo_acordo)
         where a.cliente               = p_cliente
           and a.sq_tipo_acordo_pai    is null
         order by a.nome;
   Elsif p_restricao = 'FILHO' Then
      -- Recupera os tipos de contrato do cliente, subordinados ao tipo informado
      open p_result for 
         select a.sq_tipo_acordo, a.nome, a.modalidade, a.prazo_indeterm, a.ativo, a.pessoa_juridica, 
                 a.pessoa_fisica, a.sigla, Nvl(b.Filho,0) Filho, coalesce(c.qtd,0) as qt_acordos
           from ac_tipo_acordo a
                left outer join (select sq_tipo_acordo_pai,count(*) Filho 
                                   from ac_tipo_acordo x 
                                  where cliente = p_cliente 
                                 group by sq_tipo_acordo_pai
                                ) b on (a.sq_tipo_acordo = b.sq_tipo_acordo_pai)
                left  join (select x.sq_tipo_acordo, count(x.sq_siw_solicitacao) qtd 
                              from ac_acordo x
                            group by x.sq_tipo_acordo
                           )      c on (a.sq_tipo_acordo = c.sq_tipo_acordo)
         where a.cliente               = p_cliente
           and a.sq_tipo_acordo_pai    = p_chave
         order by a.nome;
   Elsif p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
      select a.sq_tipo_acordo
        from ac_tipo_acordo a
       where a.cliente = p_cliente 
         and a.sq_tipo_acordo <> coalesce(p_chave,0)
         and ((p_nome  is null) or (p_nome  is not null and a.nome  = p_nome))
         and ((p_sigla is null) or (p_sigla is not null and a.sigla = p_sigla));
   End If;
end SP_GetAgreeType;
/
