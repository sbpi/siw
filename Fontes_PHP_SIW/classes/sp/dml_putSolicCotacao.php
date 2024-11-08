<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSolicCotacao
*
* { Description :- 
*    Manipula registros de SIW_SOLIC_COTACAO
* }
*/

class dml_putSolicCotacao {
   function getInstanceOf($dbms, $operacao, $solic, $p_moeda, $p_valor) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); 
     $sql=$strschema.'sp_putSolicCotacao';
     $params=array('p_operacao'       =>array($operacao,                B_VARCHAR,         1),
                   'p_solic'          =>array(tvl($solic),              B_INTEGER,        32),
                   'p_moeda'          =>array(tvl($p_moeda),            B_INTEGER,        32),
                   'p_valor'          =>array(toNumber(tvl($p_valor)),  B_NUMERIC,      18,2),
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
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