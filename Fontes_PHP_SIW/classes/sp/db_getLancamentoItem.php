<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getLancamentoItem
*
* { Description :- 
*    Recupera a lista de acordos do cliente
* }
*/

class db_getLancamentoItem {
   function getInstanceOf($dbms, $p_sq_documento_item, $p_sq_lancamento_doc, $p_chave, $p_sq_projeto, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETLANCAMENTOITEM';
     $params=array('p_sq_documento_item'         =>array(tvl($p_sq_documento_item),                        B_INTEGER,        32),
                   'p_sq_lancamento_doc'         =>array(tvl($p_sq_lancamento_doc),                        B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_projeto'                =>array(tvl($p_sq_projeto),                               B_INTEGER,        32),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        50),
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
