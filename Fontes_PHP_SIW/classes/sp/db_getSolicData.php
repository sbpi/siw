<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getSolicData
*
* { Description :- 
*    Recupera os dados de uma solicitacao
* }
*/

class db_getSolicData {
   function getInstanceOf($dbms, $l_chave, $l_restricao=null) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getSolicData';
     // Se não for o caso geral, altera $sql para a procedure correspondente à sigla.
     if     (substr($l_restricao,0,2)=='GC')    $sql=$strschema.'sp_getSolicDataAC';
     elseif (substr($l_restricao,0,2)=='FN')    $sql=$strschema.'sp_getSolicDataFN';
     elseif (substr($l_restricao,0,3)=='PAD')   $sql=$strschema.'sp_getSolicDataPAD';
     elseif (substr($l_restricao,0,2)=='PD')    $sql=$strschema.'sp_getSolicDataPD';
     elseif (substr($l_restricao,0,2)=='SR')    $sql=$strschema.'sp_getSolicDataSR';
     $params=array('p_chave'                     =>array($l_chave,            B_INTEGER,        32),
                   'p_restricao'                 =>array(tvl($l_restricao),   B_VARCHAR,        20),
                   'p_result'                    =>array(null,                B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
       error_reporting($l_error_reporting); 
        if ($l_rs = $l_rs->getResultArray()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}
?>
