create or replace function Acesso_Lc
  (p_chave   in number,
   p_usuario in number default null,
   p_menu    in number default null
  ) return number is
/**********************************************************************************
* Nome      : Acesso_Lc
* Finalidade: Verificar se o usu�rio t�m acesso aos dados de uma licita��o, de acordo com os par�metros informados
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      :  23/08/2004, 11:53
* Par�metros:
*    p_chave  : identifica��o da licita��o
*    p_usuario: identifica��o do usu�rio a ser testado
*    p_menu   : identifica��o da op��o de menu ligada �s licita��es
* Retorno: campo do tipo bit
*    8: Se o usu�rio � gestor do sistema
*    4: Se o usu�rio � gestor do m�dulo de licita��es
*    2: Se o usu�rio � do endere�o ao qual o registro foi vinculado
*    1: Se o usu�rio est� fazendo um acesso ao portal
*    0: Se o usu�rio n�o tem acesso ao registro
***********************************************************************************/
  w_gestor_sistema         number(2);
  w_gestor_modulo          number(2);
  w_sq_modulo              siw_menu.sq_modulo%type;
  w_cliente                siw_menu.sq_pessoa%type;
  w_endereco_registro      co_pessoa_endereco.sq_pessoa_endereco%type;
  w_endereco_usuario       co_pessoa_endereco.sq_pessoa_endereco%type;
begin
 -- Se o usu�rio n�o foi informado, ent�o � acesso pelo portal. Mostra o registro.
 If p_usuario is null Then Return 1; End If;

 -- Verifica se o usu�rio � gestor do sistema.
 select count(*) into w_gestor_sistema
   from sg_autenticacao a 
  where a.gestor_sistema = 'S'
    and a.sq_pessoa      = p_usuario;
    
 If w_gestor_sistema > 0 Then Return 8; End If;
 
 -- Recupera o m�dulo da op��o e o cliente ao qual o registro est� vinculado
 select a.sq_modulo, a.sq_pessoa into w_sq_modulo, w_cliente
   from siw_menu a
  where a.sq_menu = p_menu;

 -- Recupera o endere�o ao qual o registro est� vinculado
 select b.sq_pessoa_endereco into w_endereco_registro
   from lc_portal_lic         a
        inner join eo_unidade b on (a.sq_unidade = b.sq_unidade)
  where a.sq_portal_lic = p_chave;

 -- Recupera o endere�o da unidade � qual o usu�rio informado est� vinculado
 select b.sq_pessoa_endereco into w_endereco_usuario
   from sg_autenticacao       a
        inner join eo_unidade b on (a.sq_unidade = b.sq_unidade)
  where a.sq_pessoa = p_usuario;
  
 -- Verifica se o usu�rio � gestor do m�dulo de licita��es
 select count(*) into w_gestor_modulo
   from sg_pessoa_modulo a
  where a.sq_pessoa          = p_usuario
    and a.cliente            = w_cliente
    and a.sq_modulo          = w_sq_modulo
    and a.sq_pessoa_endereco = w_endereco_registro;
    
 If w_gestor_modulo > 0 Then Return 4; End If;
 
 -- Verifica se o usu�rio � do endere�o do registro
 If w_endereco_registro = w_endereco_usuario Then Return 2; End If;
 
 -- Retorna 0 se nenhuma condi��o foi satisfeita.
 Return 0;

end Acesso_Lc;
/

