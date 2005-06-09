create or replace procedure SP_GetCCSubordinat
   (p_cliente   in  number,
    p_sqcc      in  number   default null,
    p_restricao in  varchar2 default null,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera a os centros de custo aos quais o atual pode ser subordinado
   -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
   If upper(p_restricao) = 'TODOS' Then
      open p_result for
         select a.sq_cc sq_cc,a. nome
           from ct_cc   a
          where a.cliente = p_cliente
         order by a.nome;
   Else
      open p_result for
         select a.sq_cc sq_cc,a. nome
           from ct_cc   a
          where a.cliente = p_cliente
            and a.sq_cc not in (select a.sq_cc
                                  from ct_cc a 
                                 where a.cliente   = p_cliente
                                start with a.sq_cc = p_sqcc
                                connect by prior a.sq_cc = a.sq_cc_pai
                               )
         order by a.nome;
   End If;
end SP_GetCCSubordinat;
/

