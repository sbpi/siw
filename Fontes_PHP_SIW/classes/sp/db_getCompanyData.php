<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getCompanyData
*
* { Description :- 
*    Recupera os dados do cliente indicado.
* }
*/

class db_getCompanyData {
   function getInstanceOf($dbms, $p_cliente, $p_cnpj) {
     $sql='sp_getCompanyData';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,   null),
                   "p_cnpj"     =>array($p_cnpj,        B_VARCHAR,     20),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
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
