<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getCcSubordination
*
* { Description :- 
*    Recupera os centros de custo aos quais o atual pode ser subordinado
* }
*/

class db_getCcSubordination {
   function getInstanceOf($dbms, $p_cliente, $p_sqcc, $p_restricao) {
     $sql='sp_getCcSubordinat';
     $params=array("p_cliente"   =>array($p_cliente,     B_NUMERIC,   32),
                   "p_sqcc"      =>array($p_sqcc,        B_NUMERIC,   32),
                   "p_restricao" =>array($p_restricao,   B_VARCHAR,   10),
                   "p_result"    =>array(null,           B_CURSOR,    -1)
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
