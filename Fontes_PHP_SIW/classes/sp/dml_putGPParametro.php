<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGPParametro
*
* { Description :- 
*    Mantém a tabela de parametros
* }
*/

class dml_putGPParametro {
   function getInstanceOf($dbms, $p_cliente, $p_sq_unidade_gestao, $p_admissao_texto, 
        $p_admissao_destino, $p_rescisao_texto, $p_rescisao_destino, $p_feriado_legenda, 
        $p_feriado_nome, $p_ferias_legenda, $p_ferias_nome, $p_viagem_legenda, 
        $p_viagem_nome,$p_dias_atualizacao_cv,$p_aviso_atualizacao_cv,
        $p_tipo_tolerancia, $p_minutos_tolerancia, $p_vincula_contrato, $p_limite_diario_extras, $p_dias_perda_ferias) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_putGpParametro';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_sq_unidade_gestao'         =>array(tvl($p_sq_unidade_gestao),                        B_INTEGER,        32),
                   'p_admissao_texto'            =>array(tvl($p_admissao_texto),                           B_VARCHAR,      1000),
                   'p_admissao_destino'          =>array(tvl($p_admissao_destino),                         B_VARCHAR,       100),
                   'p_rescisao_texto'            =>array(tvl($p_rescisao_texto),                           B_VARCHAR,      1000),
                   'p_rescisao_destino'          =>array(tvl($p_rescisao_destino),                         B_VARCHAR,       100),
                   'p_feriado_legenda'           =>array(tvl($p_feriado_legenda),                          B_VARCHAR,         2),
                   'p_feriado_nome'              =>array(tvl($p_feriado_nome),                             B_VARCHAR,        20),
                   'p_ferias_legenda'            =>array(tvl($p_ferias_legenda),                           B_VARCHAR,         2),
                   'p_ferias_nome'               =>array(tvl($p_ferias_nome),                              B_VARCHAR,        20),
                   'p_viagem_legenda'            =>array(tvl($p_viagem_legenda),                           B_VARCHAR,         2),
                   'p_viagem_nome'               =>array(tvl($p_viagem_nome),                              B_VARCHAR,        20),
                   'p_dias_atualizacao_cv'       =>array(tvl($p_dias_atualizacao_cv),                      B_INTEGER,        32),
                   'p_aviso_atualizacao_cv'      =>array(tvl($p_aviso_atualizacao_cv),                     B_INTEGER,        32),
                   'p_tipo_tolerancia'           =>array(tvl($p_tipo_tolerancia),                          B_INTEGER,        32),
                   'p_minutos_tolerancia'        =>array(tvl($p_minutos_tolerancia),                       B_INTEGER,        32),
                   'p_vincula_contrato'          =>array(tvl($p_vincula_contrato),                         B_INTEGER,        32),
                   'p_limite_diario_extras'      =>array(tvl($p_limite_diario_extras),                     B_VARCHAR,         5),
                   'p_dias_perda_ferias'         =>array(tvl($p_dias_perda_ferias),                        B_INTEGER,        32)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
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
