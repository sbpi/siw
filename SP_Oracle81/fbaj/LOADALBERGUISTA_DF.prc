create or replace procedure LOADALBERGUISTA_DF is

cursor c_alberguista is
  select nome, '009'||lpad(substr(carteira,8), 7, '0') carteira, email
    from hostel_df
  minus  
  select a.nome, a.carteira, a.email
    from alberguista a,
    (   select distinct t1.nome, t1.carteira, '009'||lpad(substr(t1.carteira,8), 7, '0') carteira_join
          from hostel_df t1,
               customers_temp t2
        where t2.cstcustomerid = t1.carteira
          and lower(t2.cstfirstname) = lower(t1.nome) ) b
  where a.carteira = b.carteira_join;


begin

 for crec in c_alberguista loop
 
  begin
    insert into alberguista
    (sq_alberguista, carteira, nome, email)
    values
    (sq_alberguista.nextval, crec.carteira, crec.nome, crec.email);
    
    commit;
  exception
    when others then null;
  end;    
  
 end loop;
  
end LOADALBERGUISTA_DF;
/
