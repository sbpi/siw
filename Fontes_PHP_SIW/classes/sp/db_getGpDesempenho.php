<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getGpDesempenho
*
* { Description :- 
*    Recupera os parametros
* }
*/

class db_getGpDesempenho {
   function getInstanceOf($dbms, $p_chave, $p_ano) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_getGpDesempenho';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),     
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),     
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
