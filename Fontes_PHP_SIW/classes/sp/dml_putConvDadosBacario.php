<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putConvOutroRep
*
* { Description :- 
*    Mantém a tabela de tipos de documento
* }
*/

class dml_putConvDadosBancario {
   function getInstanceOf($dbms, $operacao, $p_restricao, $p_chave, $p_sq_banco, $p_sq_agencia, $p_op_conta, $p_nr_conta) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTCONVDADOSBANCARIO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_banco'                  =>array(tvl($p_sq_banco),                                 B_INTEGER,        32),                 
                   'p_sq_agencia'                =>array(tvl($p_sq_agencia),                               B_INTEGER,        32),
                   'p_op_conta'                  =>array(tvl($p_op_conta),                                 B_VARCHAR,         6),
                   'p_nr_conta'                  =>array(tvl($p_nr_conta),                                 B_VARCHAR,        30)
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
