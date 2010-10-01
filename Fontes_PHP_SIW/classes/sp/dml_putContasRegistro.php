<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putContasRegistro
*
* { Description :- 
*   Mantem a tabela de registros do cronograma
* }
*/

class dml_putContasRegistro {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_contas_cronograma, $p_prestacao_contas, $p_pendencia, $p_observacao,$p_usuario) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTCONTASREGISTRO';
     $params=array('p_operacao'            =>array($operacao,                                   B_VARCHAR,        30),
                   'p_chave'               =>array(tvl($p_chave),                               B_INTEGER,        32),
                   'p_contas_cronograma'   =>array(tvl($p_contas_cronograma),                   B_INTEGER,        32),
                   'p_prestacao_contas'    =>array(tvl($p_prestacao_contas),                    B_INTEGER,        32),
                   'p_pendencia'           =>array(tvl($p_pendencia),                           B_VARCHAR,         1),
                   'p_observacao'          =>array(tvl($p_observacao),                          B_VARCHAR,       2000),
                   'p_usuario'             =>array(tvl($p_usuario),                             B_INTEGER,        32)
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
