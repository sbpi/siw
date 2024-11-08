<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getLCModEnq
*
* { Description :- 
*    Recupera os enquadramentos de uma modalidade de certame
* }
*/

class db_getLCModEnq {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_sigla, $p_ativo, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getLCModEnq';
     $params=array('p_chave'             =>array($p_chave,                                 B_INTEGER,        32),
                   'p_chave_aux'         =>array(tvl($p_chave_aux),                        B_INTEGER,        32),
                   'p_sigla'             =>array(tvl($p_sigla),                            B_VARCHAR,        20),
                   'p_ativo'             =>array(tvl($p_ativo),                            B_VARCHAR,         1),       
                   'p_restricao'         =>array(tvl($p_restricao),                        B_VARCHAR,        15),
                   'p_result'            =>array(null,                                     B_CURSOR,         -1)
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
