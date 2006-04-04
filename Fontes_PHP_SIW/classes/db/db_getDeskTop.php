<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getDesktop
*
* { Description :- 
*    Recupera a mesa de trabalho de um usuário
* }
*/

class db_getDesktop {
   function getInstanceOf($dbms, $p_cliente, $p_usuario, $p_ano) {
     $sql='sp_getDesktop';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,     32),
                   "p_usuario"  =>array($p_usuario,     B_NUMERIC,     32),
                   "p_ano"      =>array($p_ano,         B_NUMERIC,     32),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        return DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, $DB_TYPE);
     }
   }
}    
?>
