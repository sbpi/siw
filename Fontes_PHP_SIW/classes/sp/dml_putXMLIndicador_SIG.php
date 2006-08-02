<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLIndicador_SIG
*
* { Description :- 
*    Mantém a tabela SIGPLAN - Indicador
* }
*/

class dml_putXMLIndicador_SIG {
   function getInstanceOf($dbms, $p_resultado, $p_cliente, $p_ano, $p_programa, $p_chave, $p_unidade_medida, $p_periodicidade, $p_base_geo, $p_nome, $p_fonte, $p_formula, $p_valor_apurado, $p_valor_ppa, $p_valor_programa, $p_valor_mes_1, $p_valor_mes_2, $p_valor_mes_3, $p_valor_mes_4, $p_valor_mes_5, $p_valor_mes_6, $p_valor_mes_7, $p_valor_mes_8, $p_valor_mes_9, $p_valor_mes_10, $p_valor_mes_11, $p_valor_mes_12, $p_apuracao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLINDICADOR_SIG';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_programa'                  =>array(tvl($p_programa),                                 B_VARCHAR,         4),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_unidade_medida'            =>array(tvl($p_unidade_medida),                           B_INTEGER,        32),
                   'p_periodicidade'             =>array(tvl($p_periodicidade),                            B_INTEGER,        32),
                   'p_base_geo'                  =>array(tvl($p_base_geo),                                 B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       200),
                   'p_fonte'                     =>array(tvl($p_fonte),                                    B_VARCHAR,       200),
                   'p_formula'                   =>array(tvl($p_formula),                                  B_VARCHAR,      4000),
                   'p_valor_apurado'             =>array(toNumber(tvl(str_replace('.',',',$p_valor_apurado)),B_NUMERIC,    18,2),
                   'p_valor_ppa'                 =>array(toNumber(tvl(str_replace('.',',',$p_valor_ppa)),  B_NUMERIC,      18,2),
                   'p_valor_programa'            =>array(toNumber(tvl(str_replace('.',',',$p_valor_programa)), B_NUMERIC,  18,2),
                   'p_valor_mes_1'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_1)),B_NUMERIC,      18,2),
                   'p_valor_mes_2'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_2)),B_NUMERIC,      18,2),
                   'p_valor_mes_3'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_3)),B_NUMERIC,      18,2),
                   'p_valor_mes_4'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_4)),B_NUMERIC,      18,2),
                   'p_valor_mes_5'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_5)),B_NUMERIC,      18,2),
                   'p_valor_mes_6'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_6)),B_NUMERIC,      18,2),
                   'p_valor_mes_7'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_7)),B_NUMERIC,      18,2),
                   'p_valor_mes_8'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_8)),B_NUMERIC,      18,2),
                   'p_valor_mes_9'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_9)),B_NUMERIC,      18,2),
                   'p_valor_mes_10'              =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_10)),B_NUMERIC,     18,2),
                   'p_valor_mes_11'              =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_11)),B_NUMERIC,     18,2),
                   'p_valor_mes_12'              =>array(toNumber(tvl(str_replace('.',',',$p_valor_mes_12)),B_NUMERIC,     18,2),
                   'p_apuracao'                  =>array(tvl($p_apuracao),                                 B_DATE,           32)
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
