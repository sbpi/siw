<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getAddressMenu
*
* { Description :- 
*    Recupera os endere�os de uma op��o do menu
* }
*/

class db_getAddressMenu {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_restricao) {
     $sql='sp_getAddressMenu';
     $params=array("p_cliente"      =>array($p_cliente,     B_NUMERIC,   32),
                   "p_chave"        =>array($p_chave,       B_NUMERIC,   32),
                   "p_restricao"    =>array($p_restricao,   B_VARCHAR,   20),
                   "p_result"       =>array(null,           B_CURSOR,    -1)
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
