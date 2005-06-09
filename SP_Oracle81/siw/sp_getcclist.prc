create or replace procedure SP_GetCCList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os centros de custo existentes
      open p_result for
         select a.sq_cc, b.nome||' - '||a.nome nome
           from ct_cc            a,
                ct_cc b
          where (a.sq_cc_pai = b.sq_cc)
            and a.ativo              = 'S'
            and a.cliente            = p_cliente
         order by 2;
   Elsif p_restricao = 'TTCENTRAL' Then
      -- Recupera os centros de custo vinculados a uma central telefônica
      open p_result for
         select a.sq_cc, b.nome||' - '||a.nome nome
           from ct_cc            a,
                ct_cc b,
                tt_cc c
          where (a.sq_cc_pai = b.sq_cc)
            and (a.sq_cc     = c.sq_cc)
            and a.ativo                 = 'S'
            and a.cliente               = p_cliente
            and c.sq_central_fone       = p_chave
         order by 2;
   Elsif p_restricao = 'TTUSUARIO' Then
      -- Recupera os centros de custo já utilizados por um usuário de central telefônica
      open p_result for
         select distinct a.sq_cc, b.nome||' - '||a.nome nome
           from ct_cc                 a,
                ct_cc      b,
                tt_ligacao c
          where (a.sq_cc_pai = b.sq_cc)
            and (a.sq_cc     = c.sq_cc)
            and a.cliente               = p_cliente
         order by 2;
   Elsif p_restricao = 'SIWSOLIC' Then
      -- Recupera os centros de custo vinculados a receita
      open p_result for
         select a.sq_cc,
                decode(c.sq_cc,null, b.nome||' - '||a.nome, c.nome||' - '||b.nome||' - '||a.nome) nome
           from ct_cc                   a,
                ct_cc b, 
                ct_cc c
          where (a.sq_cc_pai          = b.sq_cc)
            and (b.sq_cc_pai          = c.sq_cc (+))
            and a.ativo              = 'S'
            and a.cliente            = p_cliente
         order by 2;
   Elsif substr(p_restricao,1,2) = 'GC' Then
      -- Recupera os centros de custo vinculados a receita
      open p_result for
         select a.sq_cc,
                decode(c.sq_cc, null, b.nome||' - '||a.nome, c.nome||' - '||b.nome||' - '||a.nome) nome
           from ct_cc                   a,
                ct_cc b,
                ct_cc c
          where (a.sq_cc_pai          = b.sq_cc)
            and (b.sq_cc_pai          = c.sq_cc (+))
            and a.ativo      = 'S'
            and a.cliente    = p_cliente
            and ((substr(p_restricao,1,3) = 'GCD' and a.receita = 'N') or
                 (substr(p_restricao,1,3) = 'GCR' and a.receita = 'S') or
                 (substr(p_restricao,1,3) = 'GCP')
                )
         order by 2;
   End If;
end SP_GetCCList;
/

