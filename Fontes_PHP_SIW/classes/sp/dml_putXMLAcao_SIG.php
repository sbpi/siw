<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLAcao_SIG
*
* { Description :- 
*    Mantém a tabela SIGPLAN - Ação
* }
*/

class dml_putXMLAcao_SIG {
   function getInstanceOf($dbms, $p_resultado, $p_cliente, $p_ano, $p_cd_programa, $p_cd_acao, $p_cd_subacao, $p_cd_localizador, $p_cd_regiao, $p_cd_acao_ppa, $p_tipo_acao, $p_cd_produto, $p_unidade_med, $p_unidade, $p_tipo_unid, $p_estagio, $p_andamento, $p_cronograma, $p_perc_execucao, $p_desc_acao, $p_desc_subacao, $p_comentario, $p_direta, $p_descentralizada, $p_linha_credito, $p_cumulativa, $p_mes_ini, $p_ano_ini, $p_mes_fim, $p_ano_fim, $p_valor_ano_ant, $p_coment_situacao, $p_situacao_atual, $p_result_obtidos, $p_mes_conc, $p_ano_conc, $p_coment_fisica, $p_coment_financ, $p_coment_fisica_bgu, $p_coment_financ_bgu, $p_restos_pagar, $p_coment_execucao, $p_coment_restos, $p_fiscal_segur, $p_estatais, $p_outras_fontes, $p_cd_sof_ref) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLACAO_SIG';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_cd_programa'               =>array(tvl($p_cd_programa),                              B_VARCHAR,         4),
                   'p_cd_acao'                   =>array(tvl($p_cd_acao),                                  B_VARCHAR,         4),
                   'p_cd_subacao'                =>array(tvl($p_cd_subacao),                               B_VARCHAR,         4),
                   'p_cd_localizador'            =>array(tvl($p_cd_localizador),                           B_VARCHAR,         4),
                   'p_cd_regiao'                 =>array(tvl($p_cd_regiao),                                B_VARCHAR,         2),
                   'p_cd_acao_ppa'               =>array(tvl($p_cd_acao_ppa),                              B_VARCHAR,         7),
                   'p_tipo_acao'                 =>array(tvl($p_tipo_acao),                                B_INTEGER,        32),
                   'p_cd_produto'                =>array(tvl($p_cd_produto),                               B_INTEGER,        32),
                   'p_unidade_med'               =>array(tvl($p_unidade_med),                              B_INTEGER,        32),
                   'p_unidade'                   =>array(tvl($p_unidade),                                  B_VARCHAR,         5),
                   'p_tipo_unid'                 =>array(tvl($p_tipo_unid),                                B_VARCHAR,         1),
                   'p_estagio'                   =>array(tvl($p_estagio),                                  B_VARCHAR,         2),
                   'p_andamento'                 =>array(tvl($p_andamento),                                B_VARCHAR,         2),
                   'p_cronograma'                =>array(tvl($p_cronograma),                               B_VARCHAR,         2),
                   'p_perc_execucao'             =>array(tvl($p_perc_execucao),                            B_INTEGER,        32),
                   'p_desc_acao'                 =>array(tvl($p_desc_acao),                                B_VARCHAR,       255),
                   'p_desc_subacao'              =>array(tvl($p_desc_subacao),                             B_VARCHAR,       300),
                   'p_comentario'                =>array(tvl($p_comentario),                               B_VARCHAR,      4000),
                   'p_direta'                    =>array(tvl($p_direta),                                   B_VARCHAR,         1),
                   'p_descentralizada'           =>array(tvl($p_descentralizada),                          B_VARCHAR,         1),
                   'p_linha_credito'             =>array(tvl($p_linha_credito),                            B_VARCHAR,         1),
                   'p_cumulativa'                =>array(tvl($p_cumulativa),                               B_VARCHAR,         1),
                   'p_mes_ini'                   =>array(tvl($p_mes_ini),                                  B_VARCHAR,         2),
                   'p_ano_ini'                   =>array(tvl($p_ano_ini),                                  B_VARCHAR,         4),
                   'p_mes_fim'                   =>array(tvl($p_mes_fim),                                  B_VARCHAR,         2),
                   'p_ano_fim'                   =>array(tvl($p_ano_fim),                                  B_VARCHAR,         4),
                   'p_valor_ano_ant'             =>array(toNumber(tvl(str_replace('.',',',$p_valor_ano_ant))),B_NUMERIC,   18,2),
                   'p_coment_situacao'           =>array(tvl($p_coment_situacao),                          B_VARCHAR,      4000),
                   'p_situacao_atual'            =>array(tvl($p_situacao_atual),                           B_VARCHAR,      4000),
                   'p_result_obtidos'            =>array(tvl($p_result_obtidos),                           B_VARCHAR,      4000),
                   'p_mes_conc'                  =>array(tvl($p_mes_conc),                                 B_VARCHAR,         2),
                   'p_ano_conc'                  =>array(tvl($p_ano_conc),                                 B_VARCHAR,         4),
                   'p_coment_fisica'             =>array(tvl($p_coment_fisica),                            B_VARCHAR,      4000),
                   'p_coment_financ'             =>array(tvl($p_coment_financ),                            B_VARCHAR,      4000),
                   'p_coment_fisica_bgu'         =>array(tvl($p_coment_fisica_bgu),                        B_VARCHAR,      4000),
                   'p_coment_financ_bgu'         =>array(tvl($p_coment_financ_bgu),                        B_VARCHAR,      4000),
                   'p_restos_pagar'              =>array(tvl($p_restos_pagar),                             B_VARCHAR,         1),
                   'p_coment_execucao'           =>array(tvl($p_coment_execucao),                          B_VARCHAR,      4000),
                   'p_coment_restos'             =>array(tvl($p_coment_restos),                            B_VARCHAR,      4000),
                   'p_fiscap_segur'              =>array(tvl($p_fiscal_segur),                             B_VARCHAR,         1),
                   'p_estatais'                  =>array(tvl($p_estatais),                                 B_VARCHAR,         1),
                   'p_outras_fontes'             =>array(tvl($p_outras_fontes),                            B_VARCHAR,         1),
                   'p_cd_sof_ref'                =>array(tvl($p_cd_sof_ref),                               B_INTEGER,        32)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
       $Err = $l_rs->getError();
       $p_resultado = $Err['message'];
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
