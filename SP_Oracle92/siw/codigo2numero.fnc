create or replace function CODIGO2NUMERO(p_codigo in varchar2) return varchar2 is
  /* Converte o código de uma solicitação em um formato numérico para permitir sua correta ordenação.
     Normalmente o código de uma solicitação é formado por:
        Prefixo, que costuma ser finalizado com um hífen;
        Sequencial numérico;
        Ano, que costuma ser iniciado com uma barra.
     Esta função recebe o código, despreza o prefixo e monta uma string com o ano seguido do sequencial em tamanho fixo.
     Assim é possível ordenar corretamente as solicitações pelo código.
  */
  Result    varchar2(255);
  w_prefixo siw_menu.prefixo%type;
  w_numero  varchar2(255);
  w_ano     varchar2(4);
  w_tamanho number(5);
begin
  -- Recupera tamanho máximo que pode ser colocado na variável "w_prefixo", que recebe de SIW_MEBNU.PREFIXO
  select data_length into w_tamanho from user_tab_columns where table_name = 'SIW_MENU' and column_name = 'PREFIXO';
     
  If (instr(p_codigo,'-') = 0 and instr(p_codigo,'-') = 0) or instr(p_codigo,'-') > w_tamanho Then
     Result := p_codigo;
  Else
     If instr(p_codigo,'-') > 0 Then
        -- Retira o prefixo do código, que geralmente contém um hífen
        Result := lpad(substr(p_codigo, instr(p_codigo,'-')+1),20,'0');
        w_prefixo := substr(p_codigo, 1, instr(p_codigo,'-'));
     Else
        w_prefixo := '';
     End If;
     
     If instr(Result,'/') > 0 Then
        -- Se tem barra, então tem ano como sufixo
        w_numero := substr(Result, 1, instr(Result,'/')-1);
        w_ano    := lpad(substr(Result, instr(Result,'/')+1),4,'0');
     Else
        w_numero := Result;
        w_ano    := '';
     End If;
     
     Result := w_prefixo||w_ano||w_numero;
  End If;

  return(Result);
end CODIGO2NUMERO;
/
