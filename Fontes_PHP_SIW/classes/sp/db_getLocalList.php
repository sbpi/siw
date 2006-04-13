<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getLocalList
*
* { Description :- 
*    Retorna as opções do menu concedidas ao usuário indicado.
* }
*/

class db_getLocalList {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_restricao) {
     $sql='sp_getLocalList';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,     32),
                   "p_chave"    =>array($p_chave,       B_NUMERIC,     32),
                   "p_restricao"=>array($p_restricao,   B_VARCHAR,     20),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        return $l_rs->getResultData();
     }
   }
}    
?>
