<?php
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class db_getCustomerData
*
* { Description :- 
*    Recupera os dados do cliente indicado.
* }
*/

class db_getCustomerData {
   function getInstanceOf($dbms, $p_cliente) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getCustomerData';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,     32),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, $db_type=$_SESSION['DBMS']);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
     if(!$l_rs->executeQuery()) {
       error_reporting($l_error_reporting);
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
     } else {
       error_reporting($l_error_reporting); 
       if ($l_rs = $l_rs->getResultArray()) {
         return $l_rs;
       } else {
         return array();
       }
     }
   }
}    
?>
