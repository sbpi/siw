<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putContaContabil
*
* { Description :- 
*    Grava a tela de Informações Contábeis de lançamentos financeiros
* }
*/

class dml_putContaContabil {
   function getInstanceOf($dbms, $p_usuario, $p_solicitacao, $p_conta_debito, $p_conta_credito) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putContaContabil';
     $params=array('p_usuario'                =>array($p_usuario,                       B_INTEGER,        32),
                   'p_solicitacao'            =>array($p_solicitacao,                   B_INTEGER,        32),
                   'p_conta_debito'           =>array(tvl($p_conta_debito),             B_VARCHAR,        25),
                   'p_conta_credito'          =>array(tvl($p_conta_credito),            B_VARCHAR,        25)
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
