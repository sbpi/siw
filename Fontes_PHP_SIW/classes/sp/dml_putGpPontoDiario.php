<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGpPontoDiario
*
* { Description :- 
*    Mantém a tabela da folha de ponto diário. 
* }
*/

class dml_putGpPontoDiario {
   function getInstanceOf($dbms, $p_operacao, $p_contrato, $p_data, $p_primeira_entrada, $p_primeira_saida, $p_segunda_entrada, $p_segunda_saida, $p_horas_trabalhadas, $p_saldo_diario){
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_putGpPontoDiario';
     $params=array('p_operacao'                   =>array(tvl($p_operacao),                       B_VARCHAR,         1),
                   'p_contrato'                   =>array(tvl($p_contrato),                       B_INTEGER,        32),
                   'p_data'                       =>array(tvl($p_data),                           B_DATE,           32),
                   'p_primeira_entrada'           =>array(tvl($p_primeira_entrada),               B_VARCHAR,        32),
                   'p_primeira_saida'             =>array(tvl($p_primeira_saida),                 B_VARCHAR,        32),     
                   'p_segunda_entrada'            =>array(tvl($p_segunda_entrada),                B_VARCHAR,        32),
                   'p_segunda_saida'              =>array(tvl($p_segunda_saida),                  B_VARCHAR,        32),
                   'p_horas_trabalhadas'          =>array(tvl($p_horas_trabalhadas),              B_VARCHAR,        32),     
                   'p_saldo_diario'               =>array(tvl($p_saldo_diario),                   B_VARCHAR,        32)     
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
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
