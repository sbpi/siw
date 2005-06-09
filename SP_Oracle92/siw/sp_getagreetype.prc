create or replace procedure SP_GetAgreeType
   (p_chave     in number default null,
    p_chave_aux in number default null,
    p_cliente   in number,
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
   ElsIf p_restricao in ('GCRGERAL','GCDGERAL','GCPGERAL') Then
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
                 ((p_restricao = 'GCDGERAL' and a.modalidade not in ('F','I')) or
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
   Elsif p_restricao = 'SUBORDINACAO' Then
      -- Recupera os tipos de contrato do cliente para seleção de subordinação
      open p_result for 
         select a.sq_tipo_acordo, a.nome nm_tipo
           from ac_tipo_acordo a
          where a.ativo              = 'S' 
            and a.cliente            = p_cliente
            and a.sq_tipo_acordo_pai is null
            and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_tipo_acordo <> p_chave_aux))
         order by 2;
   Elsif p_restricao = 'PAI' Then
      -- Recupera os tipos de contrato do cliente que não são subordinados a ninguém
      open p_result for 
         select a.sq_tipo_acordo, a.nome, a.modalidade, a.prazo_indeterm, a.ativo, a.pessoa_juridica, 
                 a.pessoa_fisica, a.sigla, Nvl(b.Filho,0) Filho 
         from ac_tipo_acordo a
                 left outer join (select sq_tipo_acordo_pai,count(*) Filho 
                                    from ac_tipo_acordo x 
                                   where cliente = p_cliente 
                                  group by sq_tipo_acordo_pai) b 
                               on (a.sq_tipo_acordo = b.sq_tipo_acordo_pai)
         where a.cliente               = p_cliente
           and a.sq_tipo_acordo_pai    is null
         order by a.nome;
   Elsif p_restricao = 'FILHO' Then
      -- Recupera os tipos de contrato do cliente, subordinados ao tipo informado
      open p_result for 
         select a.sq_tipo_acordo, a.nome, a.modalidade, a.prazo_indeterm, a.ativo, a.pessoa_juridica, 
                 a.pessoa_fisica, a.sigla, Nvl(b.Filho,0) Filho 
         from ac_tipo_acordo a
                 left outer join (select sq_tipo_acordo_pai,count(*) Filho 
                                    from ac_tipo_acordo x 
                                   where cliente = p_cliente 
                                  group by sq_tipo_acordo_pai) b 
                               on (a.sq_tipo_acordo = b.sq_tipo_acordo_pai)
         where a.cliente               = p_cliente
           and a.sq_tipo_acordo_pai    = p_chave
         order by a.nome;
   End If;
end SP_GetAgreeType;
/

