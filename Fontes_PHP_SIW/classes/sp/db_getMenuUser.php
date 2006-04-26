<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getMenuUser
*
* { Description :- 
*    Recupera os usuários ou tipos de vínculo habilitados para uma opção do menu
* }
*/

class db_getMenuUser {
   function getInstanceOf($dbms, $p_cliente, $p_sq_menu, $p_chaveAux, $p_retorno, $p_nome, $p_sq_unidade, $p_acesso) {
     $sql='sp_getMenuUser';
     $params=array("p_cliente"      =>array($p_cliente,     B_NUMERIC,   32),
                   "p_sq_menu"      =>array($p_sq_menu,     B_NUMERIC,   32),
                   "p_chaveAux"     =>array($p_chaveAux,    B_NUMERIC,   32),
                   "p_retorno"      =>array($p_retorno,     B_VARCHAR,   20),
                   "p_nome"         =>array($p_nome,        B_VARCHAR,   60),
                   "p_sq_unidade"   =>array($p_sq_unidade,  B_NUMERIC,   32),
                   "p_acesso"       =>array($p_acesso,      B_NUMERIC,   32),
                   "p_result"       =>array(null,           B_CURSOR,    -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        if ($l_rs = $l_rs->getResultData()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}    
?>
