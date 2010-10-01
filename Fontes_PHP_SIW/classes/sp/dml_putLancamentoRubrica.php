<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putLancamentoRubrica
*
* { Description :- 
*    Mantém a tabela de rubricas por lancamento
* }
*/

class dml_putLancamentoRubrica {
   function getInstanceOf($dbms, $operacao, $p_chave_aux, $p_sq_rubrica_origem, $p_sq_rubrica_destino, $p_valor) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTLANCAMENTORUBRICA';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_sq_rubrica_origem'         =>array(tvl($p_sq_rubrica_origem),                        B_INTEGER,        32),
                   'p_sq_rubrica_destino'        =>array(tvl($p_sq_rubrica_destino),                       B_INTEGER,        32),
                   'p_valor'                     =>array(tonumber(tvl($p_valor)),                          B_NUMERIC,      18,2)
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
