<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getSiwCliModLis
*
* { Description :- 
*    Recupera a lista de m�dulos contratados por um cliente da SIW.
* }
*/

class db_getSiwCliModLis {
   function getInstanceOf($dbms, $p_cliente, $p_restricao, $p_sigla) {
     $sql='sp_getSiwCliModLis';
     $params=array("p_cliente"   =>array($p_cliente,        B_NUMERIC,     32),
                   "p_restricao" =>array($p_restricao,      B_VARCHAR,     20),
                   "p_sigla"     =>array($p_sigla,          B_VARCHAR,      3),
                   "p_result"    =>array(null,              B_CURSOR,      -1)
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
