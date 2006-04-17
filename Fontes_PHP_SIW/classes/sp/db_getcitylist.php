<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getcitylist
*
* { Description :- 
*    Retorna array com as cidades do país e estado indicado.
* }
*/

class db_getCityList {
   function getInstanceOf($dbms, $p_pais, $p_estado) {
     $sql='sp_getCityList';
     $params=array("p_pais"     =>array($p_pais,   B_NUMERIC,   null),
                   "p_estado"   =>array($p_estado, B_VARCHAR,      2),
                   "p_result"   =>array(null,      B_CURSOR,      -1)
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
