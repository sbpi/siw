<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGpPontoMensal
*
* { Description :- 
*    Mantém a tabela da folha de ponto mensal
* }
*/

class dml_putGpPontoMensal {
   function getInstanceOf($dbms, $p_operacao, $p_contrato, $p_mes, $p_horas_trabalhadas, $p_horas_extras, $p_horas_atrasos, $p_horas_banco,$p_gestor){
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putGpPontoMensal';
     $params=array('p_operacao'                 =>array(tvl($p_operacao),                     B_VARCHAR,         1),
                   'p_contrato'                 =>array(tvl($p_contrato),                     B_INTEGER,        32),
                   'p_mes'                      =>array(tvl($p_mes),                          B_VARCHAR,        6),
                   'p_horas_trabalhadas'        =>array(tvl($p_horas_trabalhadas),            B_VARCHAR,        32),     
                   'p_horas_extras'             =>array(tvl($p_horas_extras),                 B_VARCHAR,        32),
                   'p_horas_atrasos'            =>array(tvl($p_horas_atrasos),                B_VARCHAR,        32),     
                   'p_horas_banco'              =>array(tvl($p_horas_banco),                  B_VARCHAR,        32),
                   'p_gestor'                   =>array(tvl($p_gestor),                       B_INTEGER,        32)
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
