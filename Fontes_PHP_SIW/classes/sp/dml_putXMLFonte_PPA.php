<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLFonte_PPA
*
* { Description :- 
*    Mant�m a tabela PPA - Fonte
* }
*/

class dml_putXMLFonte_PPA {
   function getInstanceOf($dbms, &$p_resultado, $p_chave, $p_nome, $p_desc, $p_total) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLFONTE_PPA';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         5),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_desc'                      =>array(tvl($p_desc),                                     B_VARCHAR,        60),
                   'p_total'                     =>array(tvl($p_total),                                    B_VARCHAR,         1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
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
