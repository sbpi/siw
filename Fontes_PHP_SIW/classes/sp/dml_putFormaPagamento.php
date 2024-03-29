<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putFormaPagamento
*
* { Description :- 
*    Mant�m a tabela de tipos de documento
* }
*/

class dml_putFormaPagamento {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_nome, $p_sigla, $p_codigo_externo, $p_ativo, &$p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putFormaPagamento';
     $params=array('p_operacao'                  =>array($operacao,                                   B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                               B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                             B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                B_VARCHAR,        50),
                   'p_sigla'                     =>array(tvl($p_sigla),                               B_VARCHAR,        15),
                   'p_codigo_externo'            =>array(tvl($p_codigo_externo),                      B_VARCHAR,        60),
                   'p_ativo'                     =>array(tvl($p_ativo),                               B_VARCHAR,         1),
                   'p_chave_nova'                =>array(&$p_chave_nova,                              B_INTEGER,        32)
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
