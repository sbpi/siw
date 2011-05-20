<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMTSolicItem
*
* { Description :- 
*    Grava da tabela de itens da entrada de materiais
* }
*/

class dml_putMTSolicItem {
   function getInstanceOf($dbms, $operacao, $p_chave_aux, $p_chave, $p_chave_aux2, $p_material, $p_quantidade, 
          $p_qtd_ant, $p_valor, $p_cancelado, $p_motivo_cancelamento) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMTSolicItem';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux2'                =>array(tvl($p_chave_aux2),                               B_INTEGER,        32),
                   'p_material'                  =>array(tvl($p_material),                                 B_INTEGER,        32),
                   'p_quantidade'                =>array(tonumber(tvl($p_quantidade)),                     B_NUMERIC,      18,2),
                   'p_qtd_ant'                   =>array(tonumber(tvl($p_qtd_ant)),                        B_NUMERIC,      18,2),
                   'p_valor'                     =>array(tonumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_cancelado'                 =>array(tvl($p_cancelado),                                B_VARCHAR,         1),
                   'p_motivo_cancelamento'       =>array(tvl($p_motivo_cancelamento),                      B_VARCHAR,       500)
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
