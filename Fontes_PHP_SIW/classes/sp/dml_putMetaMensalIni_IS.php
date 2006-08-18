<?
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
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_cronogramado_1, $p_cronogramado_2, $p_cronogramado_3, $p_cronogramado_4, $p_cronogramado_5, $p_cronogramado_6, $p_cronogramado_7, $p_cronogramado_8, $p_cronogramado_9, $p_cronogramado_10, $p_cronogramado_11, $p_cronogramado_12) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTMETAMENSALINI_IS';
     $params=array('p_operacao'                  =>array($operacao,                                         B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                     B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                   B_INTEGER,        32),
                   'p_cronogramado_1'            =>array(toNumber(tvl($p_cronogramado_1)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_2'            =>array(toNumber(tvl($p_cronogramado_2)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_3'            =>array(toNumber(tvl($p_cronogramado_3)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_4'            =>array(toNumber(tvl($p_cronogramado_4)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_5'            =>array(toNumber(tvl($p_cronogramado_5)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_6'            =>array(toNumber(tvl($p_cronogramado_6)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_7'            =>array(toNumber(tvl($p_cronogramado_7)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_8'            =>array(toNumber(tvl($p_cronogramado_8)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_9'            =>array(toNumber(tvl($p_cronogramado_9)),                  B_NUMERIC,      18,4),
                   'p_cronogramado_10'           =>array(toNumber(tvl($p_cronogramado_10)),                 B_NUMERIC,      18,4),
                   'p_cronogramado_11'           =>array(toNumber(tvl($p_cronogramado_11)),                 B_NUMERIC,      18,4),
                   'p_cronogramado_12'           =>array(toNumber(tvl($p_cronogramado_12)),                 B_NUMERIC,      18,4)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
