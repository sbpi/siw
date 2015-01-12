<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLUnidade_SIG
*
* { Description :- 
*    Mantém a tabela SIGPLAN - Unidade
* }
*/

class dml_putXMLUnidade_SIG {
   function getInstanceOf($dbms, &$p_resultado, $p_ano, $p_chave, $p_tipo_unid, $p_orgao, $p_tipo_org, $p_nome) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLUNIDADE_SIG';
     $params=array('p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         5),
                   'p_tipo_unid'                 =>array(tvl($p_tipo_unid),                                B_VARCHAR,         1),
                   'p_orgao'                     =>array(tvl($p_orgao),                                    B_VARCHAR,         5),
                   'p_tipo_org'                  =>array(tvl($p_tipo_org),                                 B_VARCHAR,         1),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       110)
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
