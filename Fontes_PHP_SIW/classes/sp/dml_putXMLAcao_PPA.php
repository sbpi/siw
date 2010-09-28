<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLAcao_PPA
*
* { Description :- 
*    Mantém a tabela PPA - Ação
* }
*/

class dml_putXMLAcao_PPA {
   function getInstanceOf($dbms, $p_resultado, $p_cliente, $p_ano, $p_cd_programa, $p_chave, $p_cd_acao, $p_unidade, $p_tipo_unid, $p_funcao, $p_subfuncao, $p_tipo_acao, $p_cd_produto, $p_ds_produto, $p_unidade_med, $p_tipo_inclusao, $p_cd_esfera, $p_orgao_siorg, $p_nome, $p_finalidade, $p_descricao, $p_base_legal, $p_reperc_financ, $p_vr_reperc_financ, $p_padronizada, $p_set_padronizada, $p_direta, $p_descentralizada, $p_linha_credito, $p_transf_obrig, $p_transf_vol, $p_transf_outras, $p_despesa_obrig, $p_bloqueio_prog, $p_detalhamento, $p_mes_ini, $p_ano_ini, $p_mes_fim, $p_ano_fim, $p_valor_total, $p_valor_ano_ant, $p_qtd_ano_ant, $p_valor_ano_cor, $p_qtd_ano_cor, $p_ordem_pri, $p_observacao, $p_cd_sof, $p_qtd_total, $p_cd_sof_ref) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLACAO_PPA';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_cd_programa'               =>array(tvl($p_cd_programa),                              B_VARCHAR,         4),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         7),
                   'p_cd_acao'                   =>array(tvl($p_cd_acao),                                  B_VARCHAR,         4),
                   'p_unidade'                   =>array(tvl($p_unidade),                                  B_VARCHAR,         5),
                   'p_tipo_unid'                 =>array(tvl($p_tipo_unid),                                B_VARCHAR,         1),
                   'p_funcao'                    =>array(tvl($p_funcao),                                   B_VARCHAR,         2),
                   'p_subfuncao'                 =>array(tvl($p_subfuncao),                                B_VARCHAR,         3),
                   'p_tipo_acao'                 =>array(tvl($p_tipo_acao),                                B_INTEGER,        32),
                   'p_cd_produto'                =>array(tvl($p_cd_produto),                               B_INTEGER,        32),
                   'p_ds_produto'                =>array(tvl($p_ds_produto),                               B_VARCHAR,      4000),
                   'p_unidade_med'               =>array(tvl($p_unidade_med),                              B_INTEGER,        32),
                   'p_tipo_inclusao'             =>array(tvl($p_tipo_inclusao),                            B_INTEGER,        32),
                   'p_cd_esfera'                 =>array(tvl($p_cd_esfera),                                B_INTEGER,        32),
                   'p_orgao_siorg'               =>array(tvl($p_orgao_siorg),                              B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       255),
                   'p_finalidade'                =>array(tvl($p_finalidade),                               B_VARCHAR,      4000),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      4000),
                   'p_base_legal'                =>array(tvl($p_base_legal),                               B_VARCHAR,      4000),
                   'p_reperc_financ'             =>array(tvl($p_reperc_financ),                            B_VARCHAR,      4000),
                   'p_vr_reperc_financ'          =>array(toNumber(tvl(str_replace('.',',',$p_vr_reperc_financ))),B_NUMERIC,18,2),
                   'p_padronizada'               =>array(tvl($p_padronizada),                              B_VARCHAR,         1),
                   'p_set_padronizada'           =>array(tvl($p_set_padronizada),                          B_VARCHAR,         1),
                   'p_direta'                    =>array(tvl($p_direta),                                   B_VARCHAR,         1),
                   'p_descentralizada'           =>array(tvl($p_descentralizada),                          B_VARCHAR,         1),
                   'p_linha_credito'             =>array(tvl($p_linha_credito),                            B_VARCHAR,         1),
                   'p_transf_obrig'              =>array(tvl($p_transf_obrig),                             B_VARCHAR,         1),
                   'p_transf_vol'                =>array(tvl($p_transf_vol),                               B_VARCHAR,         1),
                   'p_transf_outras'             =>array(tvl($p_transf_outras),                            B_VARCHAR,         1),
                   'p_despesa_obrig'             =>array(tvl($p_despesa_obrig),                            B_VARCHAR,         1),
                   'p_bloqueio_prog'             =>array(tvl($p_bloqueio_prog),                            B_VARCHAR,         1),
                   'p_detalhamento'              =>array(tvl($p_detalhamento),                             B_VARCHAR,      4000),
                   'p_mes_ini'                   =>array(tvl($p_mes_ini),                                  B_VARCHAR,         2),
                   'p_ano_ini'                   =>array(tvl($p_ano_ini),                                  B_VARCHAR,         4),
                   'p_mes_fim'                   =>array(tvl($p_mes_fim),                                  B_VARCHAR,         2),
                   'p_ano_fim'                   =>array(tvl($p_ano_fim),                                  B_VARCHAR,         4),
                   'p_valor_total'               =>array(toNumber(tvl(str_replace('.',',',$p_valor_total))),B_NUMERIC,     18,2),
                   'p_valor_ano_ant'             =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_ant))),B_NUMERIC,   18,2),
                   'p_qtd_ano_ant'               =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_ant))),B_NUMERIC,     18,4),
                   'p_valor_ano_cor'             =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_cor))),B_NUMERIC,    18,2),
                   'p_qtd_ano_cor'               =>array(toNumber(tvl(str_replace('.',',',$p_qtd_ano_cor))),B_NUMERIC,     18,4),
                   'p_ordem_pri'                 =>array(tvl($p_ordem_pri),                                B_INTEGER,        32),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,      4000),
                   'p_cd_sof'                    =>array(tvl($p_cd_sof),                                   B_VARCHAR,         8),
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
