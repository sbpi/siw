<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putContasCronograma
*
* { Description :- 
*   Mantem a tabela de cronogramas da prestacao de contas
* }
*/

class dml_putContasCronograma {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_siw_solicitacao, $p_prestacao_contas, $p_inicio, $p_fim, $p_limite) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTCONTASCRONOGRAMA';
     $params=array('p_operacao'            =>array($operacao,                                   B_VARCHAR,        30),
                   'p_chave'               =>array(tvl($p_chave),                               B_INTEGER,        32),
                   'p_siw_solicitacao'     =>array(tvl($p_siw_solicitacao),                     B_INTEGER,        32),
                   'p_prestacao_contas'    =>array(tvl($p_prestacao_contas),                    B_INTEGER,        32),
                   'p_inicio'              =>array(tvl($p_inicio),                              B_DATE,           32),
                   'p_fim'                 =>array(tvl($p_fim),                                 B_DATE,           32),
                   'p_limite'              =>array(tvl($p_limite),                              B_DATE,           32)
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
