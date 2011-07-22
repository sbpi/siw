<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_puMtAjuste
*
* { Description :- 
*    Grava a tela ajuste ajuste de estoque
* }
*/

class dml_putMtAjuste {
   function getInstanceOf($dbms, $p_operacao,$p_cliente,$p_usuario,$p_chave,$p_minimo,$p_consumo,$p_ciclo,
           $p_ponto,$p_disponivel) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMtAjuste';
     $params=array('p_operacao'               =>array($p_operacao,                                B_VARCHAR,        30),
                   'p_cliente'                =>array(tvl($p_cliente),                            B_INTEGER,        32),
                   'p_usuario'                =>array(tvl($p_usuario),                            B_INTEGER,        32),
                   'p_chave'                  =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_minimo'                 =>array(tvl($p_minimo),                             B_INTEGER,        32),                   
                   'p_consumo'                =>array(tvl($p_consumo),                            B_INTEGER,        32),
                   'p_ciclo'                  =>array(tvl($p_ciclo),                              B_INTEGER,        32),
                   'p_ponto'                  =>array(tvl($p_ponto),                              B_INTEGER,        32),
                   'p_disponivel'             =>array(tvl($p_disponivel),                         B_VARCHAR,         1)
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
