<?php
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getDesktop
*
* { Description :- 
*    Recupera a mesa de trabalho de um usu�rio
* }
*/

class db_getDesktop {
   function getInstanceOf($dbms, $p_cliente, $p_usuario, $p_ano) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getDesktop';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,     32),
                   "p_usuario"  =>array($p_usuario,     B_NUMERIC,     32),
                   "p_ano"      =>array($p_ano,         B_NUMERIC,     32),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
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
