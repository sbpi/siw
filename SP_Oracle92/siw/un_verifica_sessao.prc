create or replace procedure sig.un_verifica_sessao(p_Usuario IN NUMBER, p_Inicio IN VARCHAR2 DEFAULT NULL, p_logado out varchar2) is
begin
  
  -- O terceiro parâmetro indica que o usuário está em uma aplicação fora do
  -- domínio do FABS-WEB, evitando assim erro na geração do cookie
  If seguranca.verifica_sessao(p_usuario, p_inicio, 'E') 
     Then p_logado := 'S';
     Else p_logado := 'N';
  End If;
  
end un_verifica_sessao;
/
