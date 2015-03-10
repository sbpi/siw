<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoMoeda
*
* { Description :- 
*    Manipula registros de CO_MOEDA
* }
*/

class dml_CoMoeda {
   function getInstanceOf($dbms, $operacao, $chave, $p_codigo, $p_nome, $p_sigla, $p_simbolo, 
           $p_tipo, $p_compra, $p_venda, $p_exclusao_ptax, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); 
     $sql=$strschema.'sp_putCoMoeda';
     $params=array('p_operacao'        =>array($operacao,          B_VARCHAR,      1),
                   'p_chave'           =>array($chave,             B_NUMERIC,     32),
                   'p_codigo'          =>array($p_codigo,          B_VARCHAR,      3),
                   'p_nome'            =>array($p_nome,            B_VARCHAR,     30),
                   'p_sigla'           =>array($p_sigla,           B_VARCHAR,      5),
                   'p_simbolo'         =>array($p_simbolo,         B_VARCHAR,     10),
                   'p_tipo'            =>array($p_tipo,            B_VARCHAR,      1),
                   'p_compra'          =>array($p_compra,          B_NUMERIC,     32),
                   'p_venda'           =>array($p_venda,           B_NUMERIC,     32),
                   'p_exclusao_ptax'   =>array($p_exclusao_ptax,   B_DATE,        32),
                   'p_ativo'           =>array($p_ativo,           B_VARCHAR,      1)
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