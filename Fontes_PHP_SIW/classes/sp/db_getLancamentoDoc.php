<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getLancamentoDoc
*
* { Description :- 
*    Recupera a lista de acordos do cliente
* }
*/

class db_getLancamentoDoc {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_benef, $p_tipo_doc, $p_numero, $p_data, $p_valor, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETLANCAMENTODOC';
     $params=array('p_chave'                  =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_chave_aux'              =>array(tvl($p_chave_aux),                          B_INTEGER,        32),
                   'p_benef'                  =>array(tvl($p_benef),                              B_INTEGER,        32),
                   'p_tipo_doc'               =>array(tvl($p_tipo_doc),                           B_INTEGER,        32),
                   'p_numero'                 =>array(tvl($p_numero),                             B_INTEGER,        32),
                   'p_data'                   =>array(tvl($p_data),                               B_INTEGER,        32),
                   'p_valor'                  =>array(tvl($p_valor),                              B_INTEGER,        32),
                   'p_restricao'              =>array($p_restricao,                               B_VARCHAR,        50),
                   'p_result'                 =>array(null,                                       B_CURSOR,         -1)
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
