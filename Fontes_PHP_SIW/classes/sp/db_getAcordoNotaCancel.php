<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getAcordoNotaCancel
*
* { Description :- 
*    Recupera os cancelamentos de notas
* }
*/

class db_getAcordoNotaCancel {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_chave_aux2, $p_dt_ini, $p_dt_fim, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETACORDONOTACANCEL';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        18),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        18),
                   'p_chave_aux2'                =>array(tvl($p_chave_aux2),                               B_INTEGER,        18),
                   'p_dt_ini'                    =>array(tvl($p_dt_ini),                                   B_DATE,           32),
                   'p_dt_fim'                    =>array(tvl($p_dt_fim),                                   B_DATE,           32),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        20),
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
