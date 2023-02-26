<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCaixaDevolucao
*
* { Description :- 
*    Devolve uma caixa de arquivamento para o arquivo setorial
* }
*/

class dml_putCaixaDevolucao {
   function getInstanceOf($dbms, $p_chave, $p_pessoa,$p_observacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCaixaDevolucao';
     $params=array('p_chave'                    =>array($p_chave,                                   B_INTEGER,        32),
                   'p_pessoa'                   =>array($p_pessoa,                                  B_INTEGER,        32),
                   'p_observacao'               =>array(tvl($p_observacao),                         B_VARCHAR,      2000)
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
