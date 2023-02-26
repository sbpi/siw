<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getAbastecimento
*
* { Description :- 
*    Recupera um atendimento de abastecimento
* }
*/

class db_getAbastecimento {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_cliente) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETABASTECIMENTO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        18),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        18),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        18),
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
