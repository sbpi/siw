<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getProgramaPPA_IS
*
* { Description :- 
*    Recupera os programas do ppa(tabela do SIGPLAN)
* }
*/

class db_getProgramaPPA_IS {
   function getInstanceOf($dbms, $p_chave, $p_cliente, $p_ano, $p_restricao, $p_nome, $p_macro, $p_opcao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_GETPROGRAMAPPA_IS';
     $params=array('p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_ano'                       =>array($p_ano,                                           B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         4),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        30),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       100),
                   'p_macro'                     =>array(tvl($p_macro),                                    B_VARCHAR,         2),
                   'p_opcao'                     =>array(tvl($p_opcao),                                    B_VARCHAR,         2),                   
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR);
     if(!$l_rs->executeQuery()) {
       error_reporting($l_error_reporting);
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
     } else {
       error_reporting($l_error_reporting); 
       if ($l_rs = $l_rs->getResultData()) {
         return $l_rs;
       } else {
         return array();
       }
     }
   }
}
?>
