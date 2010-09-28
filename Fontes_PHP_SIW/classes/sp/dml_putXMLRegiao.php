<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLRegiao
*
* { Description :- 
*    Mantém a tabela PPA - Região
* }
*/

class dml_putXMLRegiao {
   function getInstanceOf($dbms, $p_resultado, $p_chave, $p_nome, $p_uf, $p_regiao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLREGIAO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         2),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       120),
                   'p_uf'                        =>array(tvl($p_uf),                                       B_VARCHAR,        20),
                   'p_regiao'                    =>array(tvl($p_regiao),                                   B_VARCHAR,         2)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
