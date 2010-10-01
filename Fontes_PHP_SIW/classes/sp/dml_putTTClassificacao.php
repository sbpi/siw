<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putTTClassificacao
*
* { Description :- 
*    Mantém a tabela de vinculacao entre a central telefonica e a classificacao
* }
*/

class dml_putTTClassificacao {
   function getInstanceOf($dbms, $p_sq_cc, $p_sq_central_fone, $p_cliente) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTTTCLASSIFICACAO';
     $params=array('p_sq_cc'                     =>array(tvl($p_sq_cc),                                    B_VARCHAR,       200),
                   'p_sq_central_fone'           =>array(tvl($p_sq_central_fone),                          B_INTEGER,        18),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        18)
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
