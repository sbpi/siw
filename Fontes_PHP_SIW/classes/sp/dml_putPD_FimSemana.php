<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_FimSemana
*
* { Description :- 
*    Grava valor de reembolso de viagem.
* }
*/

class dml_putPD_FimSemana {
   function getInstanceOf($dbms, $p_chave, $p_fim_semana) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_FimSemana';
     $params=array('p_chave'                              =>array(tvl($p_chave),                         B_INTEGER,        32),
                   'p_fim_semana'                         =>array(tvl($p_fim_semana),                    B_VARCHAR,         1)
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
