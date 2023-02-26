<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMtBaixaItem
*
* { Description :- 
*    Grava itens da baixa de bens patrimoniais
* }
*/

class dml_putMtBaixaItem {
   function getInstanceOf($dbms, $operacao, $p_chave,$p_chave_aux,$p_rgp) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMtBaixaItem';
     $params=array('p_operacao'               =>array($operacao,                                  B_VARCHAR,      1),
                   'p_chave'                  =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_chave_aux'              =>array(tvl($p_chave_aux),                          B_INTEGER,        32),  
                   'p_rgp'                    =>array(tvl($p_rgp),                                B_VARCHAR,        14)
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
