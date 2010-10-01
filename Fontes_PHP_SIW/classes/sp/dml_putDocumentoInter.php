<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putDocumentoInter
*
* { Description :- 
*    Mantém a tabela de interessados de um documento
* }
*/

class dml_putDocumentoInter {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_pessoa, $p_principal) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putDocumentoInter';
     $params=array('p_operacao'        =>array($operacao,                     B_VARCHAR,         1),
                   'p_chave'           =>array($p_chave,                      B_INTEGER,        32),
                   'p_pessoa'          =>array($p_pessoa,                     B_INTEGER,        32),
                   'p_principal'       =>array(tvl($p_principal),             B_VARCHAR,         1)
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
