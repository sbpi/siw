<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPAEmpItem
*
* { Description :- 
*    Mantém a tabela principal de pedido de empréstimo de protocolos e caixas de arquivamento
* }
*/

class dml_putPAEmpItem {
   function getInstanceOf($dbms, $operacao, $p_protocolo, $p_solic, $p_devolucao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); 
     $sql=$strschema.'sp_putPAEmpItem';
     $params=array('p_operacao'             =>array($operacao,                    B_VARCHAR,         1),
                   'p_protocolo'            =>array(tvl($p_protocolo),            B_INTEGER,        32),
                   'p_solic'                =>array(tvl($p_solic),                B_INTEGER,        32),
                   'p_devolucao'            =>array($p_devolucao,                 B_DATE,           32)
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
