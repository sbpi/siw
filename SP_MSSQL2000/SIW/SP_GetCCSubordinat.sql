alter procedure dbo.SP_GetCCSubordinat
   (@p_cliente   int,
    @p_sqcc      int=null,
    @p_restricao varchar(255) = null) as

declare @level int
declare @stack table (item int not null, level int not null)
declare @result table (sq_cc int not null, pai int not null, nome varchar(255))
declare @current int
begin
   -- Recupera a os centros de custo aos quais o atual pode ser subordinado
   -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
   If upper(@p_restricao) = 'TODOS'
         select a.sq_cc sq_cc, a.nome
           from ct_cc   a
          where a.cliente = @p_cliente
         order by a.nome
   Else

      select a.sq_cc sq_cc, a.nome
        from ct_cc   a
       where a.cliente = @p_cliente
         and a.sq_cc not in (select chave from SP_fGetCC(@p_sqcc,'DOWN'))
      order by a.nome
end