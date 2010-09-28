<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putDescontoAgencia
*
* { Description :- 
*    Mantém a tabela de companhias de viagem
* }
*/

class dml_putDescontoAgencia {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_agencia, $p_inicio, $p_fim, $p_desconto, $p_ativo){
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTDescontoAgencia';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_agencia'                   =>array(tvl($p_agencia),                                  B_INTEGER,        30),
                   'p_inicio'                    =>array(toNumber(tvl($p_inicio)),                         B_NUMERIC,      18,2),
                   'p_fim'                       =>array(toNumber(tvl($p_fim)),                            B_NUMERIC,      18,2),
                   'p_desconto'                  =>array(toNumber(tvl($p_desconto)),                       B_NUMERIC,      18,2),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
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
