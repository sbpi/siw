<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putDocumentoVinc
*
* { Description :- 
*    Mantém a tabela de tipos de documento vinculada com os modulos do sistema
* }
*/

class dml_putDocumentoVinc {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_vinculo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTDocumentoVINC';
     $params=array('p_operacao'                  =>array($operacao,                                   B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                               B_INTEGER,        32),
                   'p_vinculo'                   =>array($p_vinculo,                                  B_INTEGER,        32)
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
