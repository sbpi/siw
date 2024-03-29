<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putRespAcao
*
* { Description :- 
*    Atualiza os responsaveis e seus dados na A��o do PPA, A�ao e Iniciativa
* }
*/

class dml_putRespAcao {
   function getInstanceOf($dbms, $p_chave, $p_responsavel, $p_telefone, $p_email, $p_tipo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTRESPACAO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_responsavel'               =>array(tvl($p_responsavel),                              B_VARCHAR,        60),
                   'p_telefone'                  =>array(tvl($p_telefone),                                 B_VARCHAR,        20),
                   'p_email'                     =>array(tvl($p_email),                                    B_VARCHAR,        60),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_INTEGER,        32)
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
