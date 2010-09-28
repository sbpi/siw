<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPDDiaria
*
* { Description :- 
*    Grava os dados das diárias
* }
*/

class dml_putPDDiaria {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_sq_diaria, $p_sq_cidade, $p_quantidade, $p_valor) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTPDDIARIA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_sq_diaria'                 =>array($p_sq_diaria,                                     B_INTEGER,        32),
                   'p_sq_cidade'                 =>array($p_sq_cidade,                                     B_INTEGER,        32),
                   'p_quantidade'                =>array(toNumber(tvl($p_quantidade)),                     B_NUMERIC,       5,1),
                   'p_valor'                     =>array(toNumber(tvl($p_valor)),                          B_NUMERIC,      18,2)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
