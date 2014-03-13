<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Cotacao
*
* { Description :- 
*    Grava valor estimado para os bilhetes da viagem.
* }
*/

class dml_putPD_Cotacao {
   function getInstanceOf($dbms, $p_chave, $p_moeda, $p_valor, $p_observacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_Cotacao';
     $params=array('p_chave'                   =>array(tvl($p_chave),              B_INTEGER,        32),
                   'p_moeda'                   =>array(tvl($p_moeda),              B_INTEGER,        32),
                   'p_valor'                   =>array(toNumber(tvl($p_valor)),    B_NUMERIC,        18,2),
                   'p_observacao'              =>array(tvl($p_observacao),         B_VARCHAR,      2000)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
