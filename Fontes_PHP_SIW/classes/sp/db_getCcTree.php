<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getCcTree
*
* { Description :- 
*    Recupera os centros de custo do cliente
* }
*/

class db_getCcTree {
   function getInstanceOf($dbms, $p_cliente, $p_restricao) {
     $sql='sp_getCcTree';
     $params=array("p_cliente"      =>array($p_cliente,     B_NUMERIC,   null),
                   "p_restricao"    =>array($p_restricao,   B_VARCHAR,     50),
                   "p_result"       =>array(null,           B_CURSOR,      -1)
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
