<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getPD_Alteracao
*
* { Description :- 
*    Recupera os registros de alteração de uma missão
* }
*/

class db_getPD_Alteracao {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_inicio, $p_fim, $p_numero, $p_pessoa, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getPD_Alteracao';
     $params=array('p_chave'              =>array(tvl($p_chave),                     B_INTEGER,        32),
                   'p_chave_aux'          =>array(tvl($p_chave_aux),                 B_INTEGER,        32),
                   'p_inicio'             =>array(tvl($p_inicio),                    B_DATE,           32),
                   'p_fim'                =>array(tvl($p_fim),                       B_DATE,           32),
                   'p_numero'             =>array(tvl($p_numero),                    B_VARCHAR,        20),
                   'p_pessoa'             =>array(tvl($p_pessoa),                    B_VARCHAR,        60),
                   'p_restricao'          =>array(tvl($p_restricao),                 B_VARCHAR,        20),
                   'p_result'             =>array(null,                              B_CURSOR,         -1)
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
