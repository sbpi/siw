<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getBankHouseList
*
* { Description :- 
*    Recupera as agências existentes
* }
*/

class db_getBankHouseList {
   function getInstanceOf($dbms, $p_chave, $p_ordena) {
     $sql='sp_getBankHousList';
     $params=array("p_chave"    =>array($p_chave,       B_NUMERIC,     32),
                   "p_ordena"   =>array($p_ordena,      B_VARCHAR,     40),
                   "p_codigo"   =>array($p_codigo,      B_VARCHAR,     30),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
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
