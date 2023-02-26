<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getLancamentoPais
*
* { Description :- 
*    Recupera valores do lançamento por país
* }
*/

class db_getLancamentoPais {
   function getInstanceOf($dbms, $p_cliente, $p_menu, $p_chave, $p_sq_pais, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getLancamentoPais';
     $params=array('p_cliente'                  =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_menu'                     =>array(tvl($p_menu),                                     B_INTEGER,        32),
                   'p_chave'                    =>array(tvl($p_chave),                                    B_INTEGER,         32),
                   'p_sq_pais'                  =>array(tvl($p_sq_pais),                                  B_INTEGER,        32),
                   'p_restricao'                =>array(tvl($p_restricao),                                B_VARCHAR,        15),
                   'p_result'                   =>array(null,                                             B_CURSOR,         -1)
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
