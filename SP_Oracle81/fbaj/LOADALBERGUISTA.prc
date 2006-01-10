create or replace procedure LOADALBERGUISTA is

cursor c_alberguista is
select '009'||substr(a.crdcardid, 7) carteira, substr(initcap(b.cstfirstname),1,60) nome,
       decode(b.cstdayofbirth, null, null, to_char(to_date(substr(replace(replace(replace(replace(replace(replace(replace(b.cstdayofbirth,'-FEB-','-FEV-'),'-APR-','-ABR-'),'-MAY-','-MAI-'),'-AUG-','-AGO-'),'-SEP-','-SET-'),'-OCT-','-OUT-'),'-DEC-','-DEZ-'),1,7)||19||substr(b.cstdayofbirth,8,2),'dd-mon-yyyy'),'dd-mm-yyyy')) nascimento,
       substr(b.cstaddress1,1,60) endereco,
       substr(b.cstaddress2,1,30) bairro,
       substr(b.cstzipcode,1,9) cep,
       substr(b.cstcity,1,40) cidade, b.cstphonehome fone, b.cstemail email,
       decode(b.cstgender, null, null, substr(b.cstgender,1,1)) sexo,
       to_date(a.crdissuedate, 'dd/mm/yy') carteira_emissao,
       to_date(a.crdexpirydate, 'dd/mm/yy') carteira_validade
  from cards_temp a, customers_temp b
 where a.cstcustomerid = b.cstcustomerid
   and a.crdcardid is not null
   and b.cstfirstname is not null
   and 0 = (select count(*) from alberguista x where x.carteira = a.crdcardid)
   and a.crdcardid not in (select crdcardid from ( select a.crdcardid, count(*)
                                                     from cards_temp a, customers_temp b
                                                    where a.cstcustomerid = b.cstcustomerid
                                                    group by a.crdcardid
                                                   having count(*) > 1
                                                 )
                          );


begin

for crec in c_alberguista loop

 begin
  insert into alberguista
  (sq_alberguista, carteira, nome, nascimento, endereco, bairro, cep, cidade, fone, email, sexo, carteira_emissao, carteira_validade)
  values
  (sq_alberguista.nextval, crec.carteira, crec.nome, crec.nascimento, crec.endereco, crec.bairro, crec.cep, crec.cidade, crec.fone, crec.email, crec.sexo,
   crec.carteira_emissao, crec.carteira_validade);

   commit;  

 exception
  when others then null;
 end; 

end loop;
  
end LOADALBERGUISTA;
/
