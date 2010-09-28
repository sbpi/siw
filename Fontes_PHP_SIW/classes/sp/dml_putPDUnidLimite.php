<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPDUnidLimite
*
* { Description :- 
*    Mantém os limites orçamentários para das unidades do módulo de passagens e diárias
* }
*/

class dml_putPDUnidLimite {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_limite_passagem, $p_limite_diaria, $p_ano) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTPDUNIDLIMITE';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_limite_passagem'           =>array(toNumber(tvl($p_limite_passagem)),                B_NUMERIC,      18,2),
                   'p_limite_diaria'             =>array(toNumber(tvl($p_limite_diaria)),                  B_NUMERIC,      18,2),
                   'p_ano'                       =>array($p_ano,                                           B_INTEGER,        32)
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
