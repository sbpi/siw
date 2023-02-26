<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPAElimItem
*
* { Description :- 
*    Mantém a tabela principal de pedido de eliminação de protocolos e caixas de arquivamento
* }
*/

class dml_putPAElimItem {
   function getInstanceOf($dbms, $operacao, $p_protocolo, $p_solic, $p_eliminacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); 
     $sql=$strschema.'sp_putPAElimItem';
     $params=array('p_operacao'             =>array($operacao,                    B_VARCHAR,         1),
                   'p_protocolo'            =>array(tvl($p_protocolo),            B_INTEGER,        32),
                   'p_solic'                =>array(tvl($p_solic),                B_INTEGER,        32),
                   'p_eliminacao'           =>array($p_eliminacao,                B_DATE,           32)
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
