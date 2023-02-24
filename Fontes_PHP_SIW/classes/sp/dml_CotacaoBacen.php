<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CotacaoBacen
*
* { Description :- 
*    Grava cotações importadas do Web Service do Banco Central
* }
*/

class dml_CotacaoBacen {
   function getInstanceOf($dbms, $p_cliente, $p_moeda, $p_data, $p_tipo, $p_valor) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); 
     $sql=$strschema.'SP_PutCotacaoBacen';
     $params=array('p_cliente'    =>array($p_cliente,           B_INTEGER,        32),
                   'p_moeda'      =>array($p_moeda,             B_INTEGER,        32),
                   'p_data'       =>array($p_data,              B_DATE,           32),
                   'p_tipo'       =>array($p_tipo,              B_VARCHAR,         2),
                   'p_valor'      =>array($p_valor,             B_NUMERIC,      18,4),
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, $db_type=$_SESSION["DBMS"]);
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