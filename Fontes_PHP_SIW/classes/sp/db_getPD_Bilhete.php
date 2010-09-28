<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getPD_Bilhete
*
* { Description :- 
*    Recupera os bilhetes de uma missão
* }
*/

class db_getPD_Bilhete {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_inicio, $p_fim, $p_numero, $p_cia_trans, $p_tipo, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getPD_Bilhete';
     $params=array('p_chave'              =>array(tvl($p_chave),                     B_INTEGER,        32),
                   'p_chave_aux'          =>array(tvl($p_chave_aux),                 B_INTEGER,        32),
                   'p_inicio'             =>array(tvl($p_inicio),                    B_DATE,           32),
                   'p_fim'                =>array(tvl($p_fim),                       B_DATE,           32),
                   'p_numero'             =>array(tvl($p_numero),                    B_VARCHAR,        20),
                   'p_cia_trans'          =>array(tvl($p_cia_trans),                 B_INTEGER,        32),
                   'p_tipo'               =>array(tvl($p_tipo),                      B_VARCHAR,         1),
                   'p_restricao'          =>array(tvl($p_restricao),                 B_VARCHAR,        20),
                   'p_result'             =>array(null,                              B_CURSOR,         -1)
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
