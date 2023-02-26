<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getRelBolsista
*
* { Description :- 
*    Recupera os dados para os relatórios de bolsistas
* }
*/

class db_getRelBolsista  {
   function getInstanceOf($dbms, $p_chave, $p_bolsista, $p_tema, $p_nivel, $p_contrato, $p_mes, $p_ano, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETRELBOLSISTA';
     $params=array('p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_bolsista'                  =>array($p_bolsista,                                      B_INTEGER,        32),
                   'p_tema'                      =>array($p_tema,                                          B_INTEGER,        32),
                   'p_nivel'                     =>array($p_nivel,                                         B_INTEGER,        32),
                   'p_contrato'                  =>array($p_contrato,                                      B_INTEGER,        32),
                   'p_mes'                       =>array(tvl($p_mes),                                      B_VARCHAR,        10),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_VARCHAR,        10),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        30),
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
