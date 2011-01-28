create or replace function CL_Cadastrador_Geral
  (p_menu    numeric,
   p_usuario numeric
  )  RETURNS varchar AS $$
DECLARE
/**********************************************************************************
* Nome      : CL_Cadastrador_Geral
* Finalidade: Verificar se o usuário é cadastrador geral de compras e licitações
* Autor     : Celso Miguel Lago Filho
* Data      : 24/08/2007, 15:30
* Parâmetros:
*    p_menu   : chave primária de SIW_MENU
*    p_usuario: chave de acesso do usuário
* Retorno:
*    S: O usuário pode realizar compra para qualquer pessoa
*    N: O usuário pode realizar compra apenas para ele mesmo
***********************************************************************************/
  Result                   varchar(1) := 'N';
  w_existe                 numeric(18);
  w_cliente                numeric(18);
BEGIN

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
         select sq_pessoa from cl_usuario c where c.sq_pessoa = p_usuario
         UNION
         select 1 from cl_parametro where cadastrador_geral = 'S' and cliente = w_cliente
        ) a;
 
 If w_existe > 0 Then Result := 'S'; End If;

 return(Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;