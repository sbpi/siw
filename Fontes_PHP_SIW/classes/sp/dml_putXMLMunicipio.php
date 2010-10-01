<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLMunicipio
*
* { Description :- 
*    Mantém a tabela de municípios
* }
*/

class dml_putXMLMunicipio {
   function getInstanceOf($dbms, $p_resultado, $p_chave, $p_regiao, $p_nome) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLMUNICIPIO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         7),
                   'p_regiao'                    =>array(tvl($p_regiao),                                   B_VARCHAR,         2),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        50)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       $Err = $l_rs->getError();
       $p_resultado = $Err['message'];
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
