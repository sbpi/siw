<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_RespContas
*
* { Description :- 
*    Grava alteracao do prestador de contas da viagem
* }
*/

class dml_putPD_RespContas {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_usuario, $p_pessoa, $p_justificativa) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_RespContas';
     $params=array('p_operacao'              =>array($operacao,                     B_VARCHAR,         1),
                   'p_cliente'               =>array($p_cliente,                    B_INTEGER,        32),
                   'p_chave'                 =>array($p_chave,                      B_INTEGER,        32),
                   'p_usuario'               =>array($p_usuario,                    B_INTEGER,        32),
                   'p_pessoa'                =>array($p_pessoa,                     B_INTEGER,        32),
                   'p_justificativa'         =>array($p_justificativa,              B_VARCHAR,      2000)
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
