<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putImpostoDoc
*
* { Description :- 
*    Grava a tela de impostos de um documento
* }
*/

class dml_putImpostoDoc {
   function getInstanceOf($dbms, $operacao, $p_documento, $p_imposto, $p_solic_retencao, $p_solic_imposto, $p_aliquota_total, $p_aliquota_retencao, $p_aliquota_normal,
               $p_valor_total, $p_valor_retencao, $p_valor_normal, $p_quitacao_retencao, $p_quitacao_imposto) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putImpostoDoc';
     $params=array('p_operacao'                 =>array($operacao,                                    B_VARCHAR,         1),
                   'p_documento'                =>array(tvl($p_documento),                            B_INTEGER,        32),
                   'p_imposto'                  =>array(tvl($p_imposto),                              B_INTEGER,        32),
                   'p_solic_retencao'           =>array(tvl($p_solic_retencao),                       B_INTEGER,        32),
                   'p_solic_imposto'            =>array(tvl($p_solic_imposto),                        B_INTEGER,        32),
                   'p_aliquota_total'           =>array(tonumber(nvl($p_aliquota_total,0)),           B_NUMERIC,      18,2),
                   'p_aliquota_retencao'        =>array(tonumber(nvl($p_aliquota_retencao,0)),        B_NUMERIC,      18,2),
                   'p_aliquota_normal'          =>array(tonumber(nvl($p_aliquota_normal,0)),          B_NUMERIC,      18,2),
                   'p_valor_total'              =>array(tonumber(nvl($p_valor_total,0)),              B_NUMERIC,      18,2),
                   'p_valor_retencao'           =>array(tonumber(nvl($p_valor_retencao,0)),           B_NUMERIC,      18,2),
                   'p_valor_normal'             =>array(tonumber(nvl($p_valor_normal,0)),             B_NUMERIC,      18,2),
                   'p_quitacao_retencao'        =>array(tvl($p_quitacao_retencao),                    B_DATE,           32),
                   'p_quitacao_imposto'         =>array(tvl($p_quitacao_imposto),                     B_DATE,           32)
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
