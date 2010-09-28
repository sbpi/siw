<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMetaMensalIni_IS
*
* { Description :- 
*    Mantém a tabela de atualzação mensal das metas de uma ação
* }
*/

class dml_putMetaMensalIni_IS {
   function getInstanceOf($dbms, $operacao, $l_chave, $l_cliente, $l_cronogramado_1, $l_cronogramado_2, $l_cronogramado_3, $l_cronogramado_4, $l_cronogramado_5, $l_cronogramado_6, $l_cronogramado_7, $l_cronogramado_8, $l_cronogramado_9, $l_cronogramado_10, $l_cronogramado_11, $l_cronogramado_12) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTMETAMENSALINI_IS';
     $params=array('p_operacao'                  =>array($operacao,                                         B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($l_chave),                                     B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($l_cliente),                                   B_INTEGER,        32),
                   'p_cronogramado_1'            =>array(toNumber(tvl($l_cronogramado_1)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_2'            =>array(toNumber(tvl($l_cronogramado_2)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_3'            =>array(toNumber(tvl($l_cronogramado_3)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_4'            =>array(toNumber(tvl($l_cronogramado_4)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_5'            =>array(toNumber(tvl($l_cronogramado_5)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_6'            =>array(toNumber(tvl($l_cronogramado_6)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_7'            =>array(toNumber(tvl($l_cronogramado_7)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_8'            =>array(toNumber(tvl($l_cronogramado_8)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_9'            =>array(toNumber(tvl($l_cronogramado_9)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_10'           =>array(toNumber(tvl($l_cronogramado_10)),                 B_NUMERIC,      18,4),
                   'p_cronogramado_11'           =>array(toNumber(tvl($l_cronogramado_11)),                 B_NUMERIC,      18,4),
                   'p_cronogramado_12'           =>array(toNumber(tvl($l_cronogramado_12)),                 B_NUMERIC,      18,4)
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
