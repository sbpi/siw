<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLLocalizador_PPA
*
* { Description :- 
*    Mantém a tabela PPA - Localizador
* }
*/

class dml_putXMLLocalizador_PPA {
   function getInstanceOf($dbms, $p_resultado, $p_cliente, $p_ano, $p_cd_programa, $p_cd_acao_ppa, $p_chave, $p_cd_localizador, $p_cd_regiao, $p_cd_municipio, $p_nome, $p_valor_total, $p_valor_ano_ant, $p_qtd_ano_ant, $p_valor_ano_cor, $p_qtd_ano_cor, $p_reperc_financ, $p_vr_reperc_financ, $p_mes_ini, $p_ano_ini, $p_mes_fim, $p_ano_fim, $p_nome_alterado, $p_observacao, $p_qtd_total, $p_cd_sof_ref) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLLOCALIZADOR_PPA';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_cd_programa'               =>array(tvl($p_cd_programa),                              B_VARCHAR,         4),
                   'p_cd_acao_ppa'               =>array(tvl($p_cd_acao_ppa),                              B_VARCHAR,         7),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         7),
                   'p_cd_localizador'            =>array(tvl($p_cd_localizador),                           B_VARCHAR,         4),
                   'p_cd_regiao'                 =>array(tvl($p_cd_regiao),                                B_VARCHAR,         2),
                   'p_cd_municipio'              =>array(tvl($p_cd_municipio),                             B_VARCHAR,         7),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       255),
                   'p_valor_total'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_total))),   B_NUMERIC,  18,2),
                   'p_valor_ano_ant'             =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_ant))), B_NUMERIC,  18,2),
                   'p_qtd_ano_ant'               =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_ant))),   B_NUMERIC,  18,4),
                   'p_valor_ano_cor'             =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_cor))), B_NUMERIC,  18,2),
                   'p_qtd_ano_cor'               =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_cor))),   B_NUMERIC,  18,4),
                   'p_reperc_financ'             =>array(tvl($p_reperc_financ),                            B_VARCHAR,      4000),
                   'p_vr_reperc_financ'          =>array(toNumber(tvl(str_replace('.',',',$p_vr_reperc_financ))),B_NUMERIC,18,2),
                   'p_mes_ini'                   =>array(tvl($p_mes_ini),                                  B_VARCHAR,         2),
                   'p_ano_ini'                   =>array(tvl($p_ano_ini),                                  B_VARCHAR,         4),
                   'p_mes_fim'                   =>array(tvl($p_mes_fim),                                  B_VARCHAR,         2),
                   'p_ano_fim'                   =>array(tvl($p_ano_fim),                                  B_VARCHAR,         4),
                   'p_nome_alterado'             =>array(tvl($p_nome_alterado),                            B_VARCHAR,         1),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,      4000),
                   'p_qtd_total'                 =>array(toNumber(tvl(str_replace('.',',',$p_qtd_total))), B_NUMERIC,      18,4),
                   'p_cd_sof_ref'                =>array(tvl($p_cd_sof_ref),                               B_INTEGER,        32)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
