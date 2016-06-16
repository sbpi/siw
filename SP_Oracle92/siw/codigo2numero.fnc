create or replace function CODIGO2NUMERO(p_codigo in varchar2) return varchar2 is
  /* Converte o c�digo de uma solicita��o em um formato num�rico para permitir sua correta ordena��o.
     Normalmente o c�digo de uma solicita��o � formado por:
        Prefixo, que costuma ser finalizado com um h�fen;
        Sequencial num�rico;
        Ano, que costuma ser iniciado com uma barra.
     Esta fun��o recebe o c�digo, despreza o prefixo e monta uma string com o ano seguido do sequencial em tamanho fixo.
     Assim � poss�vel ordenar corretamente as solicita��es pelo c�digo.
  */
  Result    varchar2(255);
  w_prefixo siw_menu.prefixo%type;
  w_numero  varchar2(255);
  w_ano     varchar2(4);
  w_tamanho number(5);
begin
  -- Recupera tamanho m�ximo que pode ser colocado na vari�vel "w_prefixo", que recebe de SIW_MEBNU.PREFIXO
  select data_length into w_tamanho from user_tab_columns where table_name = 'SIW_MENU' and column_name = 'PREFIXO';
     
  If (instr(p_codigo,'-') = 0 and instr(p_codigo,'-') = 0) or instr(p_codigo,'-') > w_tamanho Then
     Result := p_codigo;
  Else
     If instr(p_codigo,'-') > 0 Then
        -- Retira o prefixo do c�digo, que geralmente cont�m um h�fen
        Result := lpad(substr(p_codigo, instr(p_codigo,'-')+1),20,'0');
        w_prefixo := substr(p_codigo, 1, instr(p_codigo,'-'));
     Else
        w_prefixo := '';
     End If;
     
     If instr(Result,'/') > 0 Then
        -- Se tem barra, ent�o tem ano como sufixo
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
