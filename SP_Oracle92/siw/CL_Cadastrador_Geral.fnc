create or replace function CL_Cadastrador_Geral
  (p_menu    in number,
   p_usuario in number
  ) return varchar2 is
/**********************************************************************************
* Nome      : Gestor_Servi�o
* Finalidade: Verificar se o usu�rio � gestor de compras e licita��es
* Autor     : Celso Miguel Lago Filho
* Data      : 24/08/2007, 15:30
* Par�metros:
*    p_menu   : chave prim�ria de SIW_MENU
*    p_usuario: chave de acesso do usu�rio
* Retorno:
*    S: O usu�rio pode realizar compra para qualquer pessoa
*    N: O usu�rio pode realizar compra apenas para ele mesmo
***********************************************************************************/
  Result                   varchar2(1) := 'N';
  w_existe                 number(18);
begin

 -- Verifica se o servi�o existe
 select count(*) into w_existe from siw_menu where tramite = 'S' and sq_menu = p_menu;
 If w_existe = 0 Then Return (Result); End If;

 select count(*) into w_existe from co_pessoa where sq_pessoa = p_usuario;
 If w_existe = 0 Then Return (Result); End If;

 select count(*) into w_existe
   from (select sq_pessoa from sg_autenticacao a where a.sq_pessoa = p_usuario and a.gestor_sistema = 'S' and a.ativo = 'S'
         UNION
         select sq_pessoa from sg_pessoa_modulo b where b.sq_pessoa = p_usuario and b.sq_modulo = (select sq_modulo from siw_menu where sq_menu = p_menu)
         UNION
         select sq_pessoa from cl_usuario c where c.sq_pessoa = p_usuario
        );
 
 If w_existe > 0 Then Result := 'S'; End If;

 return(Result);
end CL_Cadastrador_Geral;
/
