<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getMenuCode
*
* { Description :- 
*    Retorna os dados de uma opção do menu a partir da sigla.
* }
*/

class db_getMenuCode {
   function getInstanceOf($dbms, $p_cliente, $p_sigla) {
     $sql='sp_getMenuCode';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,   null),
                   "p_sigla"    =>array($p_sigla,       B_VARCHAR,     20),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        $l_data = $l_rs->getResultArray();
        return $l_data;
     }
   }
}    
?>
