<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLMacro_Objetivo
*
* { Description :- 
*    Mantém a tabela SIG - Macro objetivo
* }
*/

class dml_putXMLMacro_Objetivo {
   function getInstanceOf($dbms, &$p_resultado, $p_chave, $p_nome, $p_opcao, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLMACRO_OBJETIVO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         2),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       255),
                   'p_opcao'                     =>array(tvl($p_opcao),                                    B_VARCHAR,         2),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
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
