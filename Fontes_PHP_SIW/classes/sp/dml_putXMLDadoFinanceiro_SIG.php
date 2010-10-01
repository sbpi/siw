<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLDadoFinanceiro_SIG
*
* { Description :- 
*    Mantém a tabela SIGPLAM - Dado Financeiro
* }
*/

class dml_putXMLDadoFinanceiro_SIG {
   function getInstanceOf($dbms, $p_resultado, $p_cliente, $p_ano, $p_cd_programa, $p_cd_acao, $p_cd_subacao, $p_cd_fonte, $p_cd_regiao, $p_cron_ini_mes_1, $p_cron_ini_mes_2, $p_cron_ini_mes_3, $p_cron_ini_mes_4, $p_cron_ini_mes_5, $p_cron_ini_mes_6, $p_cron_ini_mes_7, $p_cron_ini_mes_8, $p_cron_ini_mes_9, $p_cron_ini_mes_10, $p_cron_ini_mes_11, $p_cron_ini_mes_12, $p_cron_mes_1, $p_cron_mes_2, $p_cron_mes_3, $p_cron_mes_4, $p_cron_mes_5, $p_cron_mes_6, $p_cron_mes_7, $p_cron_mes_8, $p_cron_mes_9, $p_cron_mes_10, $p_cron_mes_11, $p_cron_mes_12, $p_real_mes_1, $p_real_mes_2, $p_real_mes_3, $p_real_mes_4, $p_real_mes_5, $p_real_mes_6, $p_real_mes_7, $p_real_mes_8, $p_real_mes_9, $p_real_mes_10, $p_real_mes_11, $p_real_mes_12, $p_previsao_ano, $p_cron_ini_ano, $p_atual_ano, $p_cron_ano, $p_real_ano, $p_comentario_execucao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLDADOFINANCEIRO_SIG';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_cd_programa'               =>array(tvl($p_cd_programa),                              B_VARCHAR,         4),
                   'p_cd_acao'                   =>array(tvl($p_cd_acao),                                  B_VARCHAR,         4),
                   'p_cd_subacao'                =>array(tvl($p_cd_subacao),                               B_VARCHAR,         4),
                   'p_cd_fonte'                  =>array(tvl($p_cd_fonte),                                 B_VARCHAR,         5),
                   'p_cd_regiao'                 =>array(tvl($p_cd_regiao),                                B_VARCHAR,         2),
                   'p_cron_ini_mes_1'            =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_1))),B_NUMERIC,   18,2),
                   'p_cron_ini_mes_2'            =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_2))),B_NUMERIC,   18,2),
                   'p_cron_ini_mes_3'            =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_3))),B_NUMERIC,   18,2),
                   'p_cron_ini_mes_4'            =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_4))),B_NUMERIC,   18,2),
                   'p_cron_ini_mes_5'            =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_5))),B_NUMERIC,   18,2),
                   'p_cron_ini_mes_6'            =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_6))),B_NUMERIC,   18,2),
                   'p_cron_ini_mes_7'            =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_7))),B_NUMERIC,   18,2),
                   'p_cron_ini_mes_8'            =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_8))),B_NUMERIC,   18,2),
                   'p_cron_ini_mes_9'            =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_9))),B_NUMERIC,   18,2),
                   'p_cron_ini_mes_10'           =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_10))),B_NUMERIC,  18,2),
                   'p_cron_ini_mes_11'           =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_11))),B_NUMERIC,  18,2),
                   'p_cron_ini_mes_12'           =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_mes_12))),B_NUMERIC,  18,2),
                   'p_cron_mes_1'                =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_1))), B_NUMERIC,      18,2),
                   'p_cron_mes_2'                =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_2))), B_NUMERIC,      18,2),
                   'p_cron_mes_3'                =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_3))), B_NUMERIC,      18,2),
                   'p_cron_mes_4'                =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_4))), B_NUMERIC,      18,4),
                   'p_cron_mes_5'                =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_5))), B_NUMERIC,      18,2),
                   'p_cron_mes_6'                =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_6))), B_NUMERIC,      18,2),
                   'p_cron_mes_7'                =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_7))), B_NUMERIC,      18,2),
                   'p_cron_mes_8'                =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_8))), B_NUMERIC,      18,2),
                   'p_cron_mes_9'                =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_9))), B_NUMERIC,      18,2),
                   'p_cron_mes_10'               =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_10))),B_NUMERIC,      18,2),
                   'p_cron_mes_11'               =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_11))),B_NUMERIC,      18,2),
                   'p_cron_mes_12'               =>array(toNumber(tvl(str_replace('.',',',$p_cron_mes_12))),B_NUMERIC,      18,2),
                   'p_reap_mes_1'                =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_1))), B_NUMERIC,      18,2),
                   'p_reap_mes_2'                =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_2))), B_NUMERIC,      18,2),
                   'p_reap_mes_3'                =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_3))), B_NUMERIC,      18,2),
                   'p_reap_mes_4'                =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_4))), B_NUMERIC,      18,2),
                   'p_reap_mes_5'                =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_5))), B_NUMERIC,      18,2),
                   'p_reap_mes_6'                =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_6))), B_NUMERIC,      18,2),
                   'p_reap_mes_7'                =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_7))), B_NUMERIC,      18,2),
                   'p_reap_mes_8'                =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_8))), B_NUMERIC,      18,2),
                   'p_reap_mes_9'                =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_9))), B_NUMERIC,      18,2),
                   'p_reap_mes_10'               =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_10))),B_NUMERIC,      18,2),
                   'p_reap_mes_11'               =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_11))),B_NUMERIC,      18,2),
                   'p_reap_mes_12'               =>array(toNumber(tvl(str_replace('.',',',$p_real_mes_12))),B_NUMERIC,      18,2),
                   'p_previsao_ano'              =>array(toNumber(tvl(str_replace('.',',',$p_previsao_ano))),B_NUMERIC,     18,2),
                   'p_cron_ini_ano'              =>array(toNumber(tvl(str_replace('.',',',$p_cron_ini_ano))),B_NUMERIC,     18,2),
                   'p_atuap_ano'                 =>array(toNumber(tvl(str_replace('.',',',$p_atual_ano))),  B_NUMERIC,      18,2),
                   'p_cron_ano'                  =>array(toNumber(tvl(str_replace('.',',',$p_cron_ano))),   B_NUMERIC,      18,2),
                   'p_reap_ano'                  =>array(toNumber(tvl(str_replace('.',',',$p_real_ano))),   B_NUMERIC,      18,2),
                   'p_comentario_execucao'       =>array(tvl($p_comentario_execucao),                      B_VARCHAR,      4000)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
