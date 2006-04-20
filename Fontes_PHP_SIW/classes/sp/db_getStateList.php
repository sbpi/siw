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
   function getInstanceOf($dbms, $p_sq_pais, $p_sq_regiao, $p_ativo, $p_restricao) {
     $sql='sp_getStateList';
     $params=array("p_sq_pais"   =>array($p_sq_pais,    B_NUMERIC,     32),
                   "p_sq_regiao" =>array($p_sq_regiao,  B_NUMERIC,     32),
                   "p_ativo"     =>array($p_ativo,      B_VARCHAR,     1),
                   "p_restricao" =>array($p_restricao,  B_VARCHAR,     20),
                   "p_result"    =>array(null,          B_CURSOR,      -1)
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
