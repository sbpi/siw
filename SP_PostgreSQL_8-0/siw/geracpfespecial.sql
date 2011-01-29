create or replace function GeraCPFEspecial (p_tipo varchar)  RETURNS varchar AS $$
DECLARE
  -- Gera um código equivalente ao CPF ou CNPJ, para pessoas físicas e jurídicas
  -- estrangeiras, indígenas, menores etc.
  
  -- Se p_tipo = 1, gera código equivalente a um CPF.
  -- Caso contrário, gera código equivalente a um CNPJ.
  Result varchar(10);
  w_Sequencial numeric(10);
  w_Codigo     varchar(12);
  w_DV         varchar(2);
BEGIN
  select nextVal('sq_cpf_especial') into w_Sequencial;
  If p_tipo = 1
     Then w_Codigo := substr(1000000000+w_Sequencial,2,9);
     Else w_Codigo := substr(1000000000000+w_Sequencial,2,12);
  End If;
  w_DV     := ValidaCNPJCPF(w_Codigo,1);
  w_DV     := substr(w_Dv,1,1)||substr(w_DV,2,1);
  If p_tipo = 1
     Then Result := substr(w_Codigo,4,3)||'.'||substr(w_Codigo,7,3)||'-'||w_DV;
     Else Result := substr(w_Codigo,7,2)||'/'||substr(w_Codigo,9,4)||'-'||w_DV;
  End if;   
  return(Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;