<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLOrgao_Siorg_PPA
*
* { Description :- 
*    Mantém a tabela PPA - Órgao
* }
*/

class dml_putXMLOrgao_Siorg_PPA {
   function getInstanceOf($dbms, &$p_resultado, $p_chave, $p_pai, $p_nome, $p_orgao, $p_tipo_org, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLORGAO_SIORG_PPA';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_pai'                       =>array(tvl($p_pai),                                      B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       255),
                   'p_orgao'                     =>array(tvl($p_orgao),                                    B_VARCHAR,         5),
                   'p_tipo_org'                  =>array(tvl($p_tipo_org),                                 B_VARCHAR,         1),
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
