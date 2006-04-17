<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getModData
*
* { Description :- 
*    Retorna os dados de um módulo da SIW.
* }
*/

class db_getModData {
   function getInstanceOf($dbms, $p_modulo) {
     $sql='sp_getModData';
     $params=array("p_modulo"   =>array($p_modulo,      B_NUMERIC,   32),
                   "p_result"   =>array(null,           B_CURSOR,    -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        if ($l_rs = $l_rs->getResultArray()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}    
?>
