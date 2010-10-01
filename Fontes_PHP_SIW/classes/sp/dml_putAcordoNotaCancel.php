<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoNotaCancel
*
* { Description :- 
*    Grava a tela de cancelamentos de notas de empenho do acordo
* }
*/

class dml_putAcordoNotaCancel {
   function getInstanceOf($dbms, $operacao, $p_chave_aux, $p_chave_aux2, $p_data, $p_valor) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTACORDONOTACANCEL';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,        30),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_chave_aux2'                =>array(tvl($p_chave_aux2),                               B_INTEGER,        32),
                   'p_data'                      =>array(tvl($p_data),                                     B_DATE,           32),
                   'p_valor'                     =>array(toNumber(tvl($p_valor)),                          B_NUMERIC,      18,2)
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
