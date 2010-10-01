<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getPPADadoFinanc_IS
*
* { Description :- 
*    Recupera os dados financeiros do programa PPA
* }
*/

class db_getPPADadoFinanc_IS {
   function getInstanceOf($dbms, $p_chave, $p_unidade, $ano, $cliente, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_GETPPADADOFINANC_IS';
     $params=array('p_chave'                     =>array($p_chave,                                         B_VARCHAR,         4),
                   'p_unidade'                   =>array(tvl($p_unidade),                                  B_VARCHAR,         5),
                   'p_ano'                       =>array(tvl($ano),                                        B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($cliente),                                    B_INTEGER,        32),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        20),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0);
     if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
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
