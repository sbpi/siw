<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCVIdioma
*
* { Description :- 
*    Mantém os idiomas do colaborador
* }
*/

class dml_putCVIdioma {
   function getInstanceOf($dbms, $operacao, $p_pessoa, $p_chave, $p_leitura, $p_escrita, $p_compreensao, $p_conversacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTCVIDIOMA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_pessoa'                    =>array($p_pessoa,                                        B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_leitura'                   =>array($p_leitura,                                       B_VARCHAR,         1),
                   'p_escrita'                   =>array($p_escrita,                                       B_VARCHAR,         1),
                   'p_compreensao'               =>array($p_compreensao,                                   B_VARCHAR,         1),
                   'p_conversacao'               =>array($p_conversacao,                                   B_VARCHAR,         1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
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
