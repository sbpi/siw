create or replace procedure sig.un_verifica_sessao(p_Usuario IN NUMBER, p_Inicio IN VARCHAR2 DEFAULT NULL, p_logado out varchar2) is
begin
  
  -- O terceiro par�metro indica que o usu�rio est� em uma aplica��o fora do
  -- dom�nio do FABS-WEB, evitando assim erro na gera��o do cookie
  If seguranca.verifica_sessao(p_usuario, p_inicio, 'E') 
     Then p_logado := 'S';
     Else p_logado := 'N';
  End If;
  
end un_verifica_sessao;
/
