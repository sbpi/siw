create or replace function SP_GetCCSubordinat
   (p_cliente   numeric,
    p_sqcc      numeric,
    p_restricao varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera a os centros de custo aos quais o atual pode ser subordinado
   -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
   If upper(p_restricao) = 'TODOS' Then
      open p_result for
         select a.sq_cc,a. nome
           from ct_cc   a
          where a.cliente = p_cliente
         order by a.nome;
   Else
      open p_result for
         select a.sq_cc,a. nome
           from ct_cc   a
          where a.cliente = p_cliente
            and a.sq_cc not in (select sq_cc from sp_fGetCcList(p_sqcc,0,'DOWN'))
         order by a.nome;
   End If;
   return p_result;
end; $$ language 'plpgsql' volatile;