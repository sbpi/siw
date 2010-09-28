<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLPeriodicidade_PPA
*
* { Description :- 
*    Mantém a tabela PPA - Periodicidade
* }
*/

class dml_putXMLPeriodicidade_PPA {
   function getInstanceOf($dbms, $p_resultado, $p_chave, $p_nome, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLPERIODICIDADE_PPA';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        50),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
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
