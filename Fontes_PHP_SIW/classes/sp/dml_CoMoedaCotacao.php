<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoMoedaCotacao
*
* { Description :- 
*    Manipula registros de CO_MOEDA_COTACAO
* }
*/

class dml_CoMoedaCotacao {
   function getInstanceOf($dbms, $operacao, $chave, $p_cliente, $p_moeda, $p_data, $p_taxa_compra, $p_taxa_venda) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); 
     $sql=$strschema.'sp_putCoMoedaCotacao';
     $params=array('p_operacao'       =>array($operacao,                B_VARCHAR,         1),
                   'p_chave'          =>array(tvl($chave),              B_INTEGER,        32),
                   'p_cliente'        =>array(tvl($p_cliente),          B_INTEGER,        32),
                   'p_moeda'          =>array(tvl($p_moeda),            B_INTEGER,        32),
                   'p_data'           =>array(tvl($p_data),             B_DATE,           32),
                   'p_taxa_compra'    =>array(tvl($p_taxa_compra),      B_NUMERIC,      18,2),
                   'p_taxa_venda'     =>array(tvl($p_taxa_venda),       B_NUMERIC,      18,2),
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