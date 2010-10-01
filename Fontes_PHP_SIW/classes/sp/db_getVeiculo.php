<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getVeiculo
*
* { Description :- 
*    Recupera as veículos de um cliente
* }
*/

class db_getVeiculo {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_cliente, $p_placa, $p_alugado, $p_ativo, $p_solic, $p_inicio, $p_fim, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getVeiculo';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                  B_INTEGER,        18),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                              B_INTEGER,        18),
                   'p_cliente'                   =>array($p_cliente,                                     B_INTEGER,        18),
                   'p_placa'                     =>array(tvl($p_placa),                                  B_VARCHAR,         7),
                   'p_alugado'                   =>array(tvl($p_alugado),                                B_VARCHAR,         1),
                   'p_ativo'                     =>array(tvl($p_ativo),                                  B_VARCHAR,         1),
                   'p_solic'                     =>array(tvl($p_solic),                                  B_INTEGER,        18),
                   'p_ini'                       =>array(tvl($p_ini),                                    B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                    B_DATE,           32),
                   'p_restricao'                 =>array(tvl($p_restricao),                              B_VARCHAR,        20),
                   'p_result'                    =>array(null,                                           B_CURSOR,         -1)
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
