<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putDocumentoAnexa
*
* { Description :- 
*    Registra a anexação de um documento
* }
*/

class dml_putDocumentoAnexa {
   function getInstanceOf($dbms, $chave, $usuario) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putDocumentoAnexa';
     $params=array('p_chave'              =>array(tvl($chave),                              B_INTEGER,        32),
                   'p_usuario'            =>array(tvl($usuario),                            B_INTEGER,        32)
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
