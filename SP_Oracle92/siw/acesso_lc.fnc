create or replace function Acesso_Lc
  (p_chave   in number,
   p_usuario in number default null,
   p_menu    in number default null
  ) return number is
/**********************************************************************************
* Nome      : Acesso_Lc
* Finalidade: Verificar se o usuário têm acesso aos dados de uma licitação, de acordo com os parâmetros informados
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      :  23/08/2004, 11:53
* Parâmetros:
*    p_chave  : identificação da licitação
*    p_usuario: identificação do usuário a ser testado
*    p_menu   : identificação da opção de menu ligada às licitações
* Retorno: campo do tipo bit
*    8: Se o usuário é gestor do sistema
*    4: Se o usuário é gestor do módulo de licitações
*    2: Se o usuário é do endereço ao qual o registro foi vinculado
*    1: Se o usuário está fazendo um acesso ao portal
*    0: Se o usuário não tem acesso ao registro
***********************************************************************************/
  w_gestor_sistema         number(2);
  w_gestor_modulo          number(2);
  w_sq_modulo              siw_menu.sq_modulo%type;
  w_cliente                siw_menu.sq_pessoa%type;
  w_endereco_registro      co_pessoa_endereco.sq_pessoa_endereco%type;
  w_endereco_usuario       co_pessoa_endereco.sq_pessoa_endereco%type;
begin
 -- Se o usuário não foi informado, então é acesso pelo portal. Mostra o registro.
 If p_usuario is null Then Return 1; End If;

 -- Verifica se o usuário é gestor do sistema.
 select count(*) into w_gestor_sistema
   from sg_autenticacao a 
  where a.gestor_sistema = 'S'
    and a.sq_pessoa      = p_usuario;
    
 If w_gestor_sistema > 0 Then Return 8; End If;
 
 -- Recupera o módulo da opção e o cliente ao qual o registro está vinculado
 select a.sq_modulo, a.sq_pessoa into w_sq_modulo, w_cliente
   from siw_menu a
  where a.sq_menu = p_menu;

 -- Recupera o endereço ao qual o registro está vinculado
 select b.sq_pessoa_endereco into w_endereco_registro
   from lc_portal_lic         a
        inner join eo_unidade b on (a.sq_unidade = b.sq_unidade)
  where a.sq_portal_lic = p_chave;

 -- Recupera o endereço da unidade à qual o usuário informado está vinculado
 select b.sq_pessoa_endereco into w_endereco_usuario
   from sg_autenticacao       a
        inner join eo_unidade b on (a.sq_unidade = b.sq_unidade)
  where a.sq_pessoa = p_usuario;
  
 -- Verifica se o usuário é gestor do módulo de licitações
 select count(*) into w_gestor_modulo
   from sg_pessoa_modulo a
  where a.sq_pessoa          = p_usuario
    and a.cliente            = w_cliente
    and a.sq_modulo          = w_sq_modulo
    and a.sq_pessoa_endereco = w_endereco_registro;
    
 If w_gestor_modulo > 0 Then Return 4; End If;
 
 -- Verifica se o usuário é do endereço do registro
 If w_endereco_registro = w_endereco_usuario Then Return 2; End If;
 
 -- Retorna 0 se nenhuma condição foi satisfeita.
 Return 0;

end Acesso_Lc;
/

