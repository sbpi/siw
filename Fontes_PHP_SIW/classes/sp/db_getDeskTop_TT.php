<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getDesktop_TT
*
* { Description :- 
*    Recupera a mesa de trabalho de um usuário, relativa a telefonemas.
* }
*/

class db_getDesktop_TT {
   function getInstanceOf($dbms, $p_usuario) {
     $sql='sp_getDesktop_TT';
     $params=array("p_usuario"  =>array($p_usuario,     B_NUMERIC,     32),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        return $l_rs->getResultData();
     }
   }
}    
?>
