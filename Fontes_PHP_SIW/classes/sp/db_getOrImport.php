<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getOrImport
*
* { Description :- 
*    Recupera ações do ppa
* }
*/

class db_getOrImport {
   function getInstanceOf($dbms, $p_chave, $p_cliente, $p_responsavel, $p_dt_ini, $p_dt_fim, $p_imp_ini, $p_imp_fim) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETORIMPORT';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_responsavel'               =>array(tvl($p_responsavel),                              B_VARCHAR,        60),
                   'p_dt_ini'                    =>array(tvl($p_dt_ini),                                   B_DATE,           32),
                   'p_dt_fim'                    =>array(tvl($p_dt_fim),                                   B_DATE,           32),
                   'p_imp_ini'                   =>array(tvl($p_imp_ini),                                  B_DATE,           32),
                   'p_imp_fim'                   =>array(tvl($p_imp_fim),                                  B_DATE,           32),
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
