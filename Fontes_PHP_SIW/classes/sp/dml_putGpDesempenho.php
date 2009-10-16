<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGpDesempenho
*
* { Description :- 
*    Mantém a tabela de parametros
* }
*/

class dml_putGpDesempenho {
   function getInstanceOf($dbms, $p_contrato, $p_ano, $p_percentual,$p_operacao){
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_putGpDesempenho';
     $params=array('p_contrato'                  =>array(tvl($p_contrato),                       B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                            B_INTEGER,        32),
                   'p_percentual'                =>array(tvl($p_percentual),                     B_INTEGER,        32),
                   'p_operacao'                  =>array(tvl($p_operacao),                       B_VARCHAR,         1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
