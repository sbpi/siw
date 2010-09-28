<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putDocumentoArqSet
*
* { Description :- 
*    Registra a autuação de um documento
* }
*/

class dml_putDocumentoArqSet {
   function getInstanceOf($dbms, $chave, $usuario, $p_observacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putDocumentoArqSet';
     $params=array('p_chave'              =>array(tvl($chave),                              B_INTEGER,        32),
                   'p_usuario'            =>array(tvl($usuario),                            B_INTEGER,        32),
                   'p_observacao'         =>array(tvl($p_observacao),                       B_VARCHAR,      2000)
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
