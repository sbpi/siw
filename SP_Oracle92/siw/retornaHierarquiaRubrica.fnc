create or replace function retornaHierarquiaRubrica(p_chave in number, p_direcao in varchar2 default null) return varchar2 is
--------------------------------------------------------
--p_retorno: PAIS - retorna as rubricas acima da informada
--           FILHOS - retorna as rubricas abaixo da informada
--------------------------------------------------------
  cursor c_acima is
     select sq_projeto_rubrica, sq_rubrica_pai, codigo ordem
       from pj_rubrica
     start with sq_projeto_rubrica = p_chave
     connect by prior sq_rubrica_pai = sq_projeto_rubrica;

  cursor c_abaixo is
     select sq_projeto_rubrica, sq_rubrica_pai, codigo ordem
       from pj_rubrica
     start with sq_projeto_rubrica = p_chave
     connect by prior sq_projeto_rubrica = sq_rubrica_pai;

  Result varchar2(2000) := '';
begin
  Result := ',';
  If upper(p_direcao)='PAIS' Then
     for crec in c_acima loop
         Result :=  Result||','||crec.sq_projeto_rubrica;
     end loop;
  Elsif upper(p_direcao)='FILHOS' Then
     for crec in c_abaixo loop
         Result :=  Result||','||crec.sq_projeto_rubrica;
     end loop;
  End If;
  Result := substr(Result,3);
  return(Result);
end retornaHierarquiaRubrica;
/
