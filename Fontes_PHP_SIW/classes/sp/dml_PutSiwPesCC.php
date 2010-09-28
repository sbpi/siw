<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_PutSiwPesCC
*
* { Description :- 
*    Manipula registros de SIW_PESSOA_CC
* }
*/

class dml_PutSiwPesCC {
   function getInstanceOf($dbms, $operacao, $chave, $sq_menu, $sq_cc) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putSiwPesCC';
     $params=array('operacao'           =>array($operacao,          B_VARCHAR,      1),
                   'chave'              =>array($chave,             B_NUMERIC,     32),
                   'sq_menu'            =>array($sq_menu,           B_NUMERIC,     32),
                   'sq_cc'              =>array($sq_cc,             B_NUMERIC,     32)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
