<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCVCargo
*
* { Description :- 
*    Mantém os dados de produção técnica do colaborador
* }
*/

class dml_putCVCargo {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_sq_cvpesexp, $p_sq_area_conhecimento, $p_especialidades, $p_inicio, $p_fim) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTCVCARGO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_cvpesexp'               =>array($p_sq_cvpesexp,                                   B_INTEGER,        32),
                   'p_sq_area_conhecimento'      =>array($p_sq_area_conhecimento,                          B_INTEGER,        32),
                   'p_especialidades'            =>array($p_especialidades,                                B_VARCHAR,       255),
                   'p_inicio'                    =>array($p_inicio,                                        B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32)
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
