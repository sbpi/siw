create or replace function geraSenha(p_tamanho in number) return varchar2 is
/*****************************************************************************
* Finalidade: Gerar string randômica para senhas e assinaturas de usuários
* Parâmetro : p_tamanho, obrigatório, indicando o número de bytes a ser retornado.
*             Se o tamanho for inferior ao mínimo definido na variável "tamanho_minimo" ou
*             superior a 255, é lançada uma exceção.
* Retorno   : varchar2 contendo string com letras maiúsculas e números gerados 
*             randomicamente, no tamanho indicado por p_tamanho
*****************************************************************************/
  w_tipo           number(1);
  w_numero         number(18);
  Result           varchar2(255) := '';
  tamanho_invalido EXCEPTION;
  PRAGMA EXCEPTION_INIT (tamanho_invalido,-20001);

  -- Se o tamanho mínimo mudar, basta alterar o valor da variável abaixo.
  tamanho_minimo   number(2)     := 6;
begin
  if p_tamanho < tamanho_minimo or p_tamanho > 255 Then
     -- Se o tamanho for menor que o mínimo permitido, lança exceção
     RAISE_APPLICATION_ERROR(-20001,'Informe tamanho de '||tamanho_minimo||' a 255 posições.');
  Else
     for i in 1..p_tamanho-1 loop
        w_tipo := Mod(TO_CHAR(SYSTIMESTAMP, 'SSFF9'), 3);
        If w_tipo < 1 Then
           w_numero := Mod(TO_CHAR(SYSTIMESTAMP, 'SSFF9'), 26);
           Result := Result || Chr(97+w_numero);
        Else
           w_numero := Mod(TO_CHAR(SYSTIMESTAMP, 'SSFF6'), 10);
           Result := Result || w_numero;
        End If;
     end loop;
     If w_tipo < 1 Then
        w_numero := Mod(TO_CHAR(SYSTIMESTAMP, 'SSFF6'), 10);
        Result := Result || w_numero;
     Else
        w_numero := Mod(TO_CHAR(SYSTIMESTAMP, 'SSFF9'), 26);
        Result := Result || Chr(97+w_numero);
     End If;
     return(Result);
  End If;
end geraSenha;
/
