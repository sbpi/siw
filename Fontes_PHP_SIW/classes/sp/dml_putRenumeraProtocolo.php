<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putRenumeraProtocolo
*
* { Description :- 
*    Renumera protocolo existente.
* }
*/

class dml_putRenumeraProtocolo {
   function getInstanceOf($dbms, $p_usuario, $p_chave, $p_prefixo, $p_numero, $p_ano) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putRenumeraProtocolo';
     $params=array('p_usuario'              =>array($p_usuario,                            B_INTEGER,        32),
                   'p_chave'                =>array($p_chave,                              B_INTEGER,        32),
                   'p_prefixo'              =>array($p_prefixo,                            B_VARCHAR,        5),
                   'p_numero'               =>array($p_numero,                             B_INTEGER,        32),
                   'p_ano'                  =>array($p_ano,                                B_VARCHAR,        4)
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
