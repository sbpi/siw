<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putIndicador_IS
*
* { Description :- 
*    Mantém a tabela de indicadores do programa
* }
*/

class dml_putIndicador_IS {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $ano, $cliente, $p_cd_programa, $p_cd_unidade_medida, $p_cd_periodicidade, $p_cd_base_geografica, $p_categoria_analise, $p_ordem, $p_titulo, $p_conceituacao, $p_interpretacao, $p_usos, $p_limitacoes, $p_comentarios, $p_fonte, $p_formula, $p_tipo, $p_indice_ref, $p_indice_apurado, $p_apuracao_ref, $p_apuracao_ind, $p_observacoes, $p_cumulativa, $p_quantidade, $p_exequivel, $p_situacao_atual, $p_justificativa_inex, $p_outras_medidas, $p_prev_ano_1, $p_prev_ano_2, $p_prev_ano_3, $p_prev_ano_4, $p_p1) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTINDICADOR_IS';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_ano'                       =>array($ano,                                             B_INTEGER,        32),
                   'p_cliente'                   =>array($cliente,                                         B_INTEGER,        32),
                   'p_cd_programa'               =>array($p_cd_programa,                                   B_VARCHAR,         4),
                   'p_cd_unidade_medida'         =>array(tvl($p_cd_unidade_medida),                        B_INTEGER,        32),
                   'p_cd_periodicidade'          =>array(tvl($p_cd_periodicidade),                         B_INTEGER,        32),
                   'p_cd_base_geografica'        =>array(tvl($p_cd_base_geografica),                       B_INTEGER,        32),
                   'p_categoria_analise'         =>array(tvl($p_categoria_analise),                        B_VARCHAR,      2000),
                   'p_ordem'                     =>array($p_ordem,                                         B_INTEGER,        32),
                   'p_titulo'                    =>array($p_titulo,                                        B_VARCHAR,       200),
                   'p_conceituacao'              =>array($p_conceituacao,                                  B_VARCHAR,      2000),
                   'p_interpretacao'             =>array(tvl($p_interpretacao),                            B_VARCHAR,      2000),
                   'p_usos'                      =>array(tvl($p_usos),                                     B_VARCHAR,      2000),
                   'p_limitacoes'                =>array(tvl($p_limitacoes),                               B_VARCHAR,      2000),
                   'p_comentarios'               =>array(tvl($p_comentarios),                              B_VARCHAR,      2000),
                   'p_fonte'                     =>array(tvl($p_fonte),                                    B_VARCHAR,       200),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_VARCHAR,         1),
                   'p_formula'                   =>array(tvl($p_formula),                                  B_VARCHAR,      4000),
                   'p_indice_ref'                =>array(toNumber(tvl($p_indice_ref)),                     B_NUMERIC,      18,2),
                   'p_indice_apurado'            =>array(toNumber(tvl($p_indice_apurado)),                 B_NUMERIC,      18,2),
                   'p_apuracao_ref'              =>array(tvl($p_apuracao_ref),                             B_DATE,           32),
                   'p_apuracao_ind'              =>array(tvl($p_apuracao_ind),                             B_DATE,           32),
                   'p_observacoes'               =>array(tvl($p_observacoes),                              B_VARCHAR,      4000),
                   'p_cumulativa'                =>array(tvl($p_cumulativa),                               B_VARCHAR,         1),
                   'p_quantidade'                =>array(toNumber(tvl($p_quantidade)),                     B_NUMERIC,      18,2),
                   'p_exequivel'                 =>array(tvl($p_exequivel),                                B_VARCHAR,         1),
                   'p_situacao_atual'            =>array(tvl($p_situacao_atual),                           B_VARCHAR,      4000),
                   'p_justificativa_inex'        =>array(tvl($p_justificativa_inex),                       B_VARCHAR,      1000),
                   'p_outras_medidas'            =>array(tvl($p_outras_medidas),                           B_VARCHAR,      1000),
                   'p_prev_ano_1'                =>array(toNumber(tvl($p_prev_ano_1)),                     B_NUMERIC,      18,2),
                   'p_prev_ano_2'                =>array(toNumber(tvl($p_prev_ano_2)),                     B_NUMERIC,      18,2),
                   'p_prev_ano_3'                =>array(toNumber(tvl($p_prev_ano_3)),                     B_NUMERIC,      18,2),
                   'p_prev_ano_4'                =>array(toNumber(tvl($p_prev_ano_4)),                     B_NUMERIC,      18,2),
                   'p_p1'                        =>array(tvl($p_p1),                                       B_INTEGER,        32)
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
