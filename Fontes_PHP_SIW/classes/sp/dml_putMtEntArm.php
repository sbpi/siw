<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMtEntArm
*
* { Description :- 
*    Grava o armazenamento ou estorno da entrada de materiais
* }
*/

class dml_putMtEntArm {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_item, $p_local) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMtEntArm';
     $params=array('p_operacao'                 =>array($operacao,                                  B_VARCHAR,         1),
                   'p_chave'                    =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_item'                     =>array(tvl($p_item),                               B_INTEGER,        32),
                   'p_local'                    =>array(tvl($p_local),                              B_INTEGER,        32)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
