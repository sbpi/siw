<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getLancamentoProjeto
*
* { Description :- 
*    Recupera a lista de lancamentos de um projeto.
* }
*/

class db_getLancamentoProjeto {
   function getInstanceOf($dbms, $p_chave, $p_menu, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETLANCAMENTOPROJETO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_menu'                      =>array($p_menu,                                          B_INTEGER,        32),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        50),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
