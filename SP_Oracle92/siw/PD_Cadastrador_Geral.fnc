create or replace function PD_Cadastrador_Geral
  (p_menu    in number,
   p_usuario in number
  ) return varchar2 is
/**********************************************************************************
* Nome      : Gestor_Serviço
* Finalidade: Verificar se o usuário é gestor de passagens e diárias
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      : 05/10/2005, 17:03
* Parâmetros:
*    p_menu   : chave primária de SIW_MENU
*    p_usuario: chave de acesso do usuário
* Retorno:
*    S: O usuário pode solicitar passagens e diárias para qualquer pessoa
*    N: O usuário pode solicitar passagens e diárias apenas para ele mesmo
***********************************************************************************/
  Result                   varchar2(1) := 'N';
  w_existe                 number(18);
  w_cliente                number(18);
begin

 -- Verifica se o serviço existe
 select count(*) into w_existe from siw_menu where tramite = 'S' and sq_menu = p_menu;
 If w_existe = 0 Then Return (Result); End If;

 select count(*) into w_existe from co_pessoa where sq_pessoa = p_usuario;
 If w_existe = 0 Then Return (Result); End If;
 
 select sq_pessoa into w_cliente from siw_menu where sq_menu = p_menu;

 select count(*) into w_existe
   from (select sq_pessoa from sg_autenticacao a where a.sq_pessoa = p_usuario and a.gestor_sistema = 'S' and a.ativo = 'S'
         UNION
         select sq_pessoa from sg_pessoa_modulo b where b.sq_pessoa = p_usuario and b.sq_modulo = (select sq_modulo from siw_menu where sq_menu = p_menu)
         UNION
         select sq_pessoa from pd_usuario c where c.sq_pessoa = p_usuario
         UNION
         select 1 from pd_parametro where cadastrador_geral = 'S' and cliente = w_cliente
        );
 
 If w_existe > 0 Then Result := 'S'; End If;

 return(Result);
end PD_Cadastrador_Geral;
/
