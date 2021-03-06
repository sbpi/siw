alter procedure dbo.Sp_GetSiwCliModLis
   (@p_cliente   int,
    @p_restricao varchar(50)=null,
    @p_sigla     varchar( 3)=null
   ) as
begin
   If @p_restricao is null Begin
      -- Recupera a lista de módulos contratados pelo cliente
         select a.sq_pessoa, b.sq_modulo, b.nome, b.sigla, b.objetivo_geral
           from siw_cliente_modulo    a
                inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
          where a.sq_pessoa = @p_cliente
            and (@p_sigla is null or (@p_sigla is not null and b.sigla = @p_sigla))
         order by nome;
   end else if @p_restricao = 'DISPONIVEL' Begin
      -- Recupera a lista de módulos disponíveis para compra pelo cliente
         select a.sq_modulo, a.nome, a.sigla, a.objetivo_geral 
           from siw_modulo a 
          where a.sq_modulo not in (select t1.sq_modulo
                                      from siw_modulo                    t1
                                           inner join siw_cliente_modulo t2 on (t1.sq_modulo = t2.sq_modulo)
                                     where t2.sq_pessoa = @p_cliente
                                   )
         order by 3;
   end else if @p_restricao = 'TELEFONIA' Begin
      -- Recupera a lista de módulos contratados pelo cliente
         select a.sq_pessoa, b.sq_modulo, b.nome, b.sigla, b.objetivo_geral
           from siw_cliente_modulo    a
                inner join siw_modulo b on (a.sq_modulo = b.sq_modulo and
                                            b.sigla     = 'TT'
                                           )
          where a.sq_pessoa = @p_cliente
         order by nome;
   End
/*
   If @@p_restricao is null
      -- Recupera a lista de módulos contratados pelo cliente
         select a.sq_pessoa, b.sq_modulo, b.nome, b.sigla, b.objetivo_geral
           from siw_cliente_modulo a, 
                siw_modulo         b 
          where a.sq_modulo = b.sq_modulo 
            and a.sq_pessoa = @@p_cliente
         order by nome
   Else If @@p_restricao = 'DISPONIVEL'
      -- Recupera a lista de módulos disponíveis para compra pelo cliente
         select a.sq_modulo, a.nome, a.sigla, a.objetivo_geral 
           from siw_modulo a 
          where a.sq_modulo not in (
                                     select t1.sq_modulo
                                       from siw_modulo         t1, 
                                            siw_cliente_modulo t2 
                                      where t1.sq_modulo = t2.sq_modulo 
                                        and t2.sq_pessoa = @@p_cliente)
         order by 3
*/
end
