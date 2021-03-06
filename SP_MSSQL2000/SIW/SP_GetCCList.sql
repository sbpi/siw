alter procedure dbo.SP_GetCCList
   (@p_cliente   int,
    @p_sqcc     int          = null,
    @p_restricao varchar(255) = null) as
begin
   If ISNULL(@p_restricao, '') = '' Begin
      -- Recupera os centros de custo existentes
         select a.sq_cc, b.nome+' - '+a.nome nome
           from ct_cc a, ct_cc b
          where a.sq_cc_pai          = b.sq_cc
            and a.ativo              = 'S'
            and a.cliente            = @p_cliente
         order by nome
      End
   If @p_restricao = 'TTCENTRAL' Begin
      -- Recupera os centros de custo vinculados a uma central telefônica
         select a.sq_cc, b.nome+' - '+a.nome nome
           from ct_cc a, ct_cc b, tt_cc c
          where a.sq_cc_pai             = b.sq_cc
            and a.sq_cc                 = c.sq_cc
            and a.ativo                 = 'S'
            and a.cliente               = @p_cliente
            and c.sq_central_fone       = @p_sqcc
         order by nome
      End
   If @p_restricao = 'TTUSUARIO' Begin
      -- Recupera os centros de custo já utilizados por um usuário de central telefônica
         select distinct a.sq_cc, b.nome+' - '+a.nome nome
           from ct_cc a, ct_cc b, tt_ligacao c
          where a.sq_cc_pai             = b.sq_cc
            and a.sq_cc                 = c.sq_cc
            and a.cliente               = @p_cliente
         order by nome
      End
   If @p_restricao = 'SIWSOLIC' Begin
      -- Recupera os centros de custo vinculados a receita
         select a.sq_cc, b.nome+' - '+a.nome nome
           from ct_cc a, ct_cc b
          where a.sq_cc_pai          = b.sq_cc
            and a.ativo              = 'S'
            and a.cliente            = @p_cliente
         order by nome
      End
end