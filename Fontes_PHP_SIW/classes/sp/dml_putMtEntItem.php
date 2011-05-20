<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMtEntItem
*
* { Description :- 
*    Grava da tabela de itens da entrada de materiais
* }
*/

class dml_putMtEntItem {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_almoxarifado, $p_situacao, $p_material, $p_quantidade, 
          $p_valor, $p_fator, $p_validade, $p_fabricacao, $p_vida_util, $p_lote, $p_marca, $p_modelo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMtEntItem';
     $params=array('p_operacao'                 =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                    =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_almoxarifado'             =>array(tvl($p_almoxarifado),                             B_INTEGER,        32),
                   'p_situacao'                 =>array(tvl($p_situacao),                                 B_INTEGER,        32),
                   'p_material'                 =>array(tvl($p_material),                                 B_INTEGER,        32),
                   'p_quantidade'               =>array(tonumber(tvl($p_quantidade)),                     B_INTEGER,        32),
                   'p_valor'                    =>array(tonumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_fator'                    =>array(tonumber(tvl($p_fator)),                          B_INTEGER,        32),
                   'p_validade'                 =>array(tonumber(tvl($p_validade)),                       B_INTEGER,        32),
                   'p_fabricacao'               =>array(tonumber(tvl($p_fabricacao)),                     B_DATE,           32),
                   'p_vida_util'                =>array(tonumber(tvl($p_vida_util)),                      B_DATE,           32),
                   'p_lote'                     =>array(tvl($p_lote),                                     B_VARCHAR,        20),
                   'p_marca'                    =>array(tvl($p_marca),                                    B_VARCHAR,        50),
                   'p_modelo'                   =>array(tvl($p_modelo),                                   B_VARCHAR,        50)
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
