<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getConversao
*
* { Description :- 
*    Converte valor de uma moeda para outra na data informada
* }
*/

class db_getConversao {
   function getInstanceOf($dbms, $p_cliente, $p_data, $p_origem, $p_destino, $p_valor, $p_taxa) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql='FUNCTION '.$strschema.'CONVERSAO';
     $params=array('p_cliente'        =>array($p_cliente,           B_INTEGER,        32),
                   'p_data'           =>array($p_data,              B_DATE,           32),
                   'p_origem'         =>array($p_origem,            B_INTEGER,        32),
                   'p_destino'        =>array($p_destino,           B_INTEGER,        32),
                   'p_valor'          =>array(toNumber($p_valor),   B_NUMERIC,        18, 2),
                   'p_taxa'           =>array($p_taxa,              B_VARCHAR,         1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
       error_reporting($l_error_reporting); 
       if ($l_rs = $l_rs->getResultArray()) {
         foreach($l_rs as $k => $v) { 
           return $v;
         }
       } else {
         return 0;
       }
     }
   }
}
?>
