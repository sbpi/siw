<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getCodigo
*
* { Description :- 
*    Recupera a lista de módulos contratados por um cliente da SIW.
* }
*/

class db_getCodigo {
   function getInstanceOf($dbms, $p_cliente, $p_restricao, $p_chave_interna, $p_chave_aux) {
     $sql='sp_getCodigo';
     $params=array("p_cliente"       =>array($p_cliente,       B_NUMERIC,     32),
                   "p_restricao"     =>array($p_restricao,     B_VARCHAR,     20),
                   "p_chave_interna" =>array($p_chave_interna, B_VARCHAR,    255),
                   "p_chave_aux"     =>array($p_chave_aux,     B_VARCHAR,    255),
                   "p_result"        =>array(null,             B_CURSOR,      -1)
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
