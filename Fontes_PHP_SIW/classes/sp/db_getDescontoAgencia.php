<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getDescontoAgencia
*
* { Description :- 
*    Recupera as taxas de desconto oferecidades pelas Agências de Viagem
* }
*/

class db_getDescontoAgencia {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_agencia, $p_percentual, $p_inicio, $p_fim, $p_desconto, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getDescontoAgencia';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_agencia'                   =>array(tvl($p_agencia),                                  B_INTEGER,        30),
                   'p_percentual'                =>array(toNumber(tvl($p_percentual)),                     B_NUMERIC,      18,2),                   
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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