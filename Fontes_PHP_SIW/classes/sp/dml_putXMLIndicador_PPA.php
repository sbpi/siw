<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLIndicador_PPA
*
* { Description :- 
*    Mantém a tabela PPA - Indicador
* }
*/

class dml_putXMLIndicador_PPA {
   function getInstanceOf($dbms, $p_resultado, $p_cliente, $p_ano, $p_programa, $p_chave, $p_unidade_med, $p_periodicidade, $p_base_geo, $p_nome, $p_fonte, $p_formula, $p_valor_ano_1, $p_valor_ano_2, $p_valor_ano_3, $p_valor_ano_4, $p_valor_ano_5, $p_valor_ano_6, $p_valor_ref, $p_valor_final, $p_apurado_ano_1, $p_apurado_ano_2, $p_apurado_ano_3, $p_apurado_ano_4, $p_apurado_ano_5, $p_apurado_ano_6, $p_apurado_ref, $p_apurado_final, $p_apuracao, $p_observacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLINDICADOR_PPA';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_programa'                  =>array(tvl($p_programa),                                 B_VARCHAR,         4),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_unidade_med'               =>array(tvl($p_unidade_med),                              B_INTEGER,        32),
                   'p_periodicidade'             =>array(tvl($p_periodicidade),                            B_INTEGER,        32),
                   'p_base_geo'                  =>array(tvl($p_base_geo),                                 B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       200),
                   'p_fonte'                     =>array(tvl($p_fonte),                                    B_VARCHAR,       200),
                   'p_formula'                   =>array(tvl($p_formula),                                  B_VARCHAR,      4000),
                   'p_valor_ano_1'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_1))), B_NUMERIC,    18,2),
                   'p_valor_ano_2'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_2))), B_NUMERIC,    18,2),
                   'p_valor_ano_3'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_3))), B_NUMERIC,    18,2),
                   'p_valor_ano_4'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_4))), B_NUMERIC,    18,2),
                   'p_valor_ano_5'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_5))), B_NUMERIC,    18,2),
                   'p_valor_ano_6'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_6))), B_NUMERIC,    18,2),
                   'p_valor_ref'                 =>array(toNumber(tvl(str_replace('.',',',$p_valor_ref))),   B_NUMERIC,    18,2),
                   'p_valor_final'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_final))), B_NUMERIC,    18,2),
                   'p_apurado_ano_1'             =>array(tvl($p_apurado_ano_1),                            B_VARCHAR,         1),
                   'p_apurado_ano_2'             =>array(tvl($p_apurado_ano_2),                            B_VARCHAR,         1),
                   'p_apurado_ano_3'             =>array(tvl($p_apurado_ano_3),                            B_VARCHAR,         1),
                   'p_apurado_ano_4'             =>array(tvl($p_apurado_ano_4),                            B_VARCHAR,         1),
                   'p_apurado_ano_5'             =>array(tvl($p_apurado_ano_5),                            B_VARCHAR,         1),
                   'p_apurado_ano_6'             =>array(tvl($p_apurado_ano_6),                            B_VARCHAR,         1),
                   'p_apurado_ref'               =>array(tvl($p_apurado_ref),                              B_VARCHAR,         1),
                   'p_apurado_final'             =>array(tvl($p_apurado_final),                            B_VARCHAR,         1),
                   'p_apuracao'                  =>array(tvl(str_replace('T',' ',$p_apuracao)),            B_VARCHAR,         20),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,      4000)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       $Err = $l_rs->getError();
       $p_resultado = $Err['message'];
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
