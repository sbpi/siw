<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getGpFamiliares
*
* { Description :- 
*    Recupera os parametros
* }
*/

class db_getGpFamiliares {
   function getInstanceOf($dbms, $p_chave, $p_cliente,$p_colaborador) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_getGpFamiliares';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),     
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),     
                   'p_colaborador'               =>array(tvl($p_colaborador),                              B_INTEGER,        32),     
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