<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoTermo
*
* { Description :- 
*    Grava a tela de termo de referência
* }
*/

class dml_putAcordoTermo {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_atividades, $p_produtos, $p_requisitos, $p_codigo_externo, $p_vincula_projeto, $p_vincula_demanda, $p_vincula_viagem, $p_prestacao_contas) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTACORDOTERMO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_atividades'                =>array(tvl($p_atividades),                               B_VARCHAR,      2000),
                   'p_produtos'                  =>array(tvl($p_produtos),                                 B_VARCHAR,      2000),
                   'p_requisitos'                =>array(tvl($p_requisitos),                               B_VARCHAR,      2000),
                   'p_vincula_projeto'           =>array(tvl($p_vincula_projeto),                          B_VARCHAR,         1),
                   'p_vincula_demanda'           =>array(tvl($p_vincula_demanda),                          B_VARCHAR,         1),
                   'p_vincula_viagem'            =>array(tvl($p_vincula_viagem),                           B_VARCHAR,         1),
                   'p_prestacao_contas'          =>array(tvl($p_prestacao_contas),                         B_VARCHAR,         1),
                   'p_codigo_externo'            =>array(tvl($p_codigo_externo),                           B_VARCHAR,        60)
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
