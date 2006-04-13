<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getStateList
*
* { Description :- 
*    Recupera as cidades existentes em relação a um país
* }
*/

class db_getStateList {
   function getInstanceOf($dbms, $p_sq_pais) {
     $sql='sp_getStateList';
     $params=array("p_sq_pais"  =>array($p_sq_pais,     B_NUMERIC,     32),
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
