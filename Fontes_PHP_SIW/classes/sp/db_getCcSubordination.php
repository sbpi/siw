<?php
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class db_getCcSubordination
*
* { Description :- 
*    Recupera os centros de custo aos quais o atual pode ser subordinado
* }
*/

class db_getCcSubordination {
   function getInstanceOf($dbms, $p_cliente, $p_sqcc, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getCcSubordinat';
     $params=array("p_cliente"   =>array($p_cliente,     B_NUMERIC,   32),
                   "p_sqcc"      =>array($p_sqcc,        B_NUMERIC,   32),
                   "p_restricao" =>array($p_restricao,   B_VARCHAR,   10),
                   "p_result"    =>array(null,           B_CURSOR,    -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
     if(!$l_rs->executeQuery()) {
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
     } else {
       error_reporting($l_error_reporting); 
        if ($l_rs = $l_rs->getResultData()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}    
?>
