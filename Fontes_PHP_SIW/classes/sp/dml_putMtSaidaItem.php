<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMtSaidaItem
*
* { Description :- 
*    Grava da tabela de itens da solicitacao
* }
*/

class dml_putMtSaidaItem {
   function getInstanceOf($dbms, $operacao, $p_saida, $p_estoque, $p_local, $p_item, $p_solicitacao, $p_material, $p_fator, $p_solicitada, $p_entregue, $p_efetivacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMtSaidaItem';
     $params=array('p_operacao'                 =>array($operacao,                                    B_VARCHAR,         1),
                   'p_saida'                    =>array(tvl($p_saida),                                B_INTEGER,        32),
                   'p_estoque'                  =>array(tvl($p_estoque),                              B_INTEGER,        32),
                   'p_local'                    =>array(tvl($p_local),                                B_INTEGER,        32),
                   'p_item'                     =>array(tvl($p_item),                                 B_INTEGER,        32),
                   'p_solicitacao'              =>array(tvl($p_solicitacao),                          B_INTEGER,        32),
                   'p_material'                 =>array(tvl($p_material),                             B_INTEGER,        32),
                   'p_fator'                    =>array(tvl($p_fator),                                B_INTEGER,        32),
                   'p_solicitada'               =>array(tonumber(tvl($p_solicitada)),                 B_NUMERIC,        32),
                   'p_entregue'                 =>array(tonumber(tvl($p_entregue)),                   B_NUMERIC,        32),
                   'p_efetivacao'               =>array(tvl($p_efetivacao),                           B_DATE,           32)
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
