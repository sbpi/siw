<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getCustomerSite
*
* { Description :- 
*    This class retrieves the data of the selected client
* }
*/

class db_getCustomerSite {
   function getInstanceOf($dbms, $p_cliente) {
     $sql='sp_getCustomerSite';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,   null),
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
