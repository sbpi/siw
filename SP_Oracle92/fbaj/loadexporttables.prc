create or replace procedure LOADEXPORTTABLES is

cursor c_Alberguistas is
  select *
    from alberguista;

begin
  
  
 for crec in c_Alberguistas loop
 
 
   insert into customers
   (cstcustomerid, cststscode, csttitle, cstfirstname, cstlastname, cstaddress1, 
    cstaddress2, cstzipcode, cstcity, cstcountry, cstphonework, cstphonehome, 
    cstphonemobile, cstemail, cstdayofbirth, cstgender, cstchildren, cstcomment, 
    lgnstscode, lgnpassword)
   values
   ('009'||lpad(substr(crec.carteira, 4), 10, '0'), 'Normal', null, subname(crec.nome, 'F'), subname(crec.nome, 'L'), 
    crec.endereco, crec.bairro, crec.cep, crec.cidade, 'Brazil', null, crec.fone,
    null, crec.email, case when crec.nascimento is not null then to_char(crec.nascimento, 'yyyy-mm-dd') else null end, case when crec.sexo = 'M' then 'Male' when crec.sexo = 'F' then 'Female' else null end,
    null, null, 'Normal', null);
    
    
    
   insert into cards
   (crdcardid, cstcustomerid, stscode, crdissuedate, crdexpirydate, crdbusinessrules, 
    crdcomment)
   values
   ('009'||lpad(substr(crec.carteira, 4), 10, '0'), '009'||lpad(substr(crec.carteira, 4), 10, '0'), 
    'Normal', crec.carteira_emissao, crec.carteira_validade, '1', null); 
 
 
 end loop; 
  
  
  
  
  
end LOADEXPORTTABLES;
/

