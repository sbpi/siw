<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CoBanco
*
* { Description :- 
*    Manipula registros de CO_BANCO
* }
*/

class dml_CoBanco {
   function getInstanceOf($dbms, $operacao, $chave, $p_nome, $p_codigo, $p_padrao, $p_ativo, $p_exige) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCoBanco';
     $params=array('p_operacao'        =>array($operacao,          B_VARCHAR,      1),
                   'p_chave'           =>array($chave,             B_NUMERIC,     32),
                   'p_nome'            =>array($p_nome,            B_VARCHAR,     60),
                   'p_codigo'          =>array($p_codigo,          B_VARCHAR,     30),
                   'p_padrao'          =>array($p_padrao,          B_VARCHAR,      1),
                   'p_ativo'           =>array($p_ativo,           B_VARCHAR,      1),
                   'p_exige'           =>array($p_exige,           B_VARCHAR,      1)
                   
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
