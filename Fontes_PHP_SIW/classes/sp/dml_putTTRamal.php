<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putTTRamal
*
* { Description :- 
*    Mantém a tabela de Ramais
* }
*/

class dml_putTTRamal {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_sq_central_fone, $p_codigo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTTTRAMAL';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        18),
                   'p_sq_central_fone'           =>array(tvl($p_sq_central_fone),                          B_INTEGER,        18),
                   'p_codigo'                   =>array(tvl($p_codigo),                                    B_VARCHAR,         4)
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
