<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getSegList
*
* { Description :- 
*    Recupera a lista de segmentos
* }
*/

class db_getSegList {
   function getInstanceOf($dbms, $p_ativo) {
     $sql='sp_getSegList';
     $params=array("p_ativo"    =>array($p_ativo,       B_VARCHAR,    1),
                   "p_result"   =>array(null,           B_CURSOR,    -1)
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
