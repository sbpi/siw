<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getModList
*
* { Description :- 
*    Recupera a lista de módulos
* }
*/

class db_getModList {
   function getInstanceOf($dbms) {
     $sql='sp_getModList';
     $params=array("p_result"   =>array(null,           B_CURSOR,    -1));
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
