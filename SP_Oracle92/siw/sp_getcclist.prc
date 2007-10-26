create or replace procedure SP_GetCCList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os centros de custo existentes
      open p_result for 
         select a.sq_cc, b.nome||' - '||a.nome nome
           from ct_cc            a
                inner join ct_cc b on (a.sq_cc_pai = b.sq_cc)
          where a.ativo              = 'S'
            and a.cliente            = p_cliente
         order by 2;
   Elsif p_restricao = 'TTCENTRAL' Then
      -- Recupera os centros de custo vinculados a uma central telefônica
      open p_result for 
         select a.sq_cc, b.nome||' - '||a.nome nome
           from ct_cc            a
                inner join ct_cc b on (a.sq_cc_pai   = b.sq_cc)
                inner join tt_cc c on (a.sq_cc       = c.sq_cc and
                                       c.desativacao is null
                                      )
          where a.ativo                 = 'S'
            and a.cliente               = p_cliente
            and c.sq_central_fone       = p_chave
         order by 2;
   Elsif p_restricao = 'TTUSUARIO' Then
      -- Recupera os centros de custo já utilizados por um usuário de central telefônica
      open p_result for 
         select distinct a.sq_cc, b.nome||' - '||a.nome nome
           from ct_cc                 a
                inner join ct_cc      b on (a.sq_cc_pai = b.sq_cc)
                inner join tt_ligacao c on (a.sq_cc     = c.sq_cc)
          where a.cliente               = p_cliente
         order by 2;
   Elsif p_restricao = 'SIWSOLIC' Then
      -- Recupera os centros de custo vinculados a receita
      open p_result for 
         select a.sq_cc, 
                case when c.sq_cc is not null
                     then c.nome||' - '||b.nome||' - '||a.nome
                     else case when b.sq_cc is not null
                               then b.nome||' - '||a.nome
                               else a.nome
                          end
                end nome
           from ct_cc                   a
                left outer   join ct_cc b on (a.sq_cc_pai          = b.sq_cc)
                  left outer join ct_cc c on (b.sq_cc_pai          = c.sq_cc)
          where a.ativo              = 'S'
            and a.cliente            = p_cliente
         order by 2;
   Elsif substr(p_restricao,1,2) = 'GC' Then
      -- Recupera os centros de custo vinculados a receita
      open p_result for 
         select a.sq_cc, 
                case when c.sq_cc is not null
                     then c.nome||' - '||b.nome||' - '||a.nome
                     else b.nome||' - '||a.nome
                end nome
           from ct_cc                   a
                inner        join ct_cc b on (a.sq_cc_pai          = b.sq_cc)
                  left outer join ct_cc c on (b.sq_cc_pai          = c.sq_cc)
          where a.ativo      = 'S'
            and a.cliente    = p_cliente
            and ((substr(p_restricao,1,3) = 'GCD' and a.receita = 'N') or
                 (substr(p_restricao,1,3) = 'GCR' and a.receita = 'S') or
                 (substr(p_restricao,1,3) = 'GCP')
                )
         order by 2;
   End If;
end SP_GetCCList;
/
