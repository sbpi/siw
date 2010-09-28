<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getParametro
*
* { Description :- 
*    Recupera os parametros do módulo desejado
* }
*/

class db_getParametro {
   function getInstanceOf($dbms, $p_cliente, $p_modulo, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETPARAMETRO';
     $params=array('p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_modulo'                    =>array(tvl($p_modulo),                                   B_VARCHAR,         3),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        20),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0);
     if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
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
