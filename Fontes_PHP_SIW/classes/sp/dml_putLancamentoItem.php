<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putLancamentoItem
*
* { Description :- 
*    Grava a tela de documentos
* }
*/

class dml_putLancamentoItem {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_sq_projeto_rubrica, $p_descricao, $p_quantidade, 
          $p_valor_unitario, $p_ordem, $p_data_cotacao, $p_valor_cotacao, $p_solic_item) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putLancamentoItem';
     $params=array('p_operacao'                 =>array($operacao,                                  B_VARCHAR,         1),
                   'p_chave'                    =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_chave_aux'                =>array(tvl($p_chave_aux),                          B_INTEGER,        32),
                   'p_sq_projeto_rubrica'       =>array(tvl($p_sq_projeto_rubrica),                 B_INTEGER,        32),
                   'p_descricao'                =>array(tvl($p_descricao),                          B_VARCHAR,       500),
                   'p_quantidade'               =>array(tonumber(tvl($p_quantidade)),               B_NUMERIC,      18,2),
                   'p_valor_unitario'           =>array(tonumber(tvl($p_valor_unitario)),           B_NUMERIC,      18,2),
                   'p_ordem'                    =>array(tvl($p_ordem),                              B_INTEGER,        32),
                   'p_data_cotacao'             =>array(tvl($p_data_cotacao),                       B_DATE,           32),
                   'p_valor_cotacao'            =>array(tonumber(tvl($p_valor_cotacao)),            B_NUMERIC,      18,2),
                   'p_solic_item'               =>array(tvl($p_solic_item),                         B_INTEGER,        32)
                   );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
