<?php
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getMoedaCotacao
*
* { Description :- 
*    Recupera cotações das unidades monetárias.
* }
*/

class db_getMoedaCotacao {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_moeda, $p_inicio, $p_fim, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getMoedaCotacao';
     $params=array('p_cliente'    =>array($p_cliente,           B_INTEGER,     32),
                   'p_chave'      =>array(tvl($p_chave),        B_INTEGER,     32),
                   'p_moeda'      =>array(tvl($p_moeda),        B_INTEGER,     32),
                   'p_inicio'     =>array(tvl($p_inicio),       B_DATE,        32),
                   'p_fim'        =>array(tvl($p_fim),          B_DATE,        32),
                   'p_restricao'  =>array(tvl($p_restricao),    B_VARCHAR,     30),
                   'p_result'     =>array(null,                 B_CURSOR,      -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
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