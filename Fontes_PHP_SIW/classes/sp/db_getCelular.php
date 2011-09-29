<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getCelular
*
* { Description :- 
*    Recupera os celulares de um cliente
* }
*/

class db_getCelular {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_chave_aux, $p_numero, $p_pendencia, $p_ativo, $p_solic, $p_inicio, $p_fim, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getCelular';
     $params=array('p_cliente'                   =>array($p_cliente,                        B_INTEGER,        18),
                   'p_chave'                     =>array(tvl($p_chave),                     B_INTEGER,        18),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                 B_INTEGER,        18),
                   'p_numero'                    =>array(tvl($p_numero),                    B_VARCHAR,        20),
                   'p_pendencia'                 =>array(tvl($p_pendencia),                 B_VARCHAR,         1),
                   'p_ativo'                     =>array(tvl($p_ativo),                     B_VARCHAR,         1),
                   'p_solic'                     =>array(tvl($p_solic),                     B_INTEGER,        18),
                   'p_ini'                       =>array(tvl($p_ini),                       B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                       B_DATE,           32),
                   'p_restricao'                 =>array(tvl($p_restricao),                 B_VARCHAR,        20),
                   'p_result'                    =>array(null,                              B_CURSOR,         -1)
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
