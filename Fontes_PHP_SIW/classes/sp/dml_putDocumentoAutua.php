<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putDocumentoAutua
*
* { Description :- 
*    Registra a autuação de um documento
* }
*/

class dml_putDocumentoAutua {
   function getInstanceOf($dbms, $chave, $unidade, $usuario, $p_descricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putDocumentoAutua';
     $params=array('p_chave'              =>array(tvl($chave),                              B_INTEGER,        32),
                   'p_unidade'            =>array(tvl($unidade),                            B_INTEGER,        32),
                   'p_usuario'            =>array(tvl($usuario),                            B_INTEGER,        32),
                   'p_descricao'          =>array(tvl($p_descricao),                        B_VARCHAR,      2000)
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
