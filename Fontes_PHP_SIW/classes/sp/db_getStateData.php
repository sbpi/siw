<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getStateData
*
* { Description :- 
*    Recupera os dados do estado
* }
*/

class db_getStateData {
   function getInstanceOf($dbms, $p_sq_pais, $p_co_uf) {
     $sql='sp_getStateData';
     $params=array("p_sq_pais"  =>array($p_sq_pais,       B_NUMERIC,   32),
                   "p_co_uf"    =>array($p_co_uf,         B_VARCHAR,    3),
                   "p_result"   =>array(null,             B_CURSOR,    -1)
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
