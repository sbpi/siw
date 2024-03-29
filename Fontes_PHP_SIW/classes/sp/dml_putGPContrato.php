<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGPContrato
*
* { Description :- 
*    Mant�m os dados do contrato de um colaborador
* }
*/

class dml_putGPContrato {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_cc, $p_sq_pessoa, $p_sq_posto_trabalho, 
        $p_sq_modalidade_contrato, $p_sq_unidade_lotacao, $p_sq_unidade_exercicio, $p_sq_localizacao, 
        $p_matricula, $p_inicio, $p_fim, $p_trata_username, $p_trata_ferias, $p_trata_extras, 
        $p_tipo_vinculo,$p_entrada_manha,$p_saida_manha,$p_entrada_tarde,$p_saida_tarde,$p_entrada_noite,
        $p_saida_noite,$p_sabado, $p_domingo, $p_banco_horas_saldo,$p_banco_horas_data,$p_remuneracao_inicial,
        $p_data_atestado,$p_dias_experiencia,$p_vale_refeicao,$p_vale_transporte,
        $p_seguro_saude,$p_seguro_odonto,$p_seguro_vida,$p_plano_saude,$p_plano_odonto,$p_plano_vida,
        $p_observacao_beneficios
        ){
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_putGPContrato';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cc'                        =>array(tvl($p_cc),                                       B_INTEGER,        32),
                   'p_sq_pessoa'                 =>array(tvl($p_sq_pessoa),                                B_INTEGER,        32),
                   'p_sq_posto_trabalho'         =>array(tvl($p_sq_posto_trabalho),                        B_INTEGER,        32),
                   'p_sq_modalidade_contrato'    =>array(tvl($p_sq_modalidade_contrato),                   B_INTEGER,        32),
                   'p_sq_unidade_lotacao'        =>array(tvl($p_sq_unidade_lotacao),                       B_INTEGER,        32),
                   'p_sq_unidade_exercicio'      =>array(tvl($p_sq_unidade_exercicio),                     B_INTEGER,        32),
                   'p_sq_localizacao'            =>array(tvl($p_sq_localizacao),                           B_INTEGER,        32),
                   'p_matricula'                 =>array(tvl($p_matricula),                                B_VARCHAR,        20),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_trata_username'            =>array($p_trata_username,                                B_VARCHAR,         1),
                   'p_trata_ferias'              =>array($p_trata_ferias,                                  B_VARCHAR,         1),
                   'p_trata_extras'              =>array($p_trata_extras,                                  B_VARCHAR,         1),
                   'p_tipo_vinculo'              =>array(tvl($p_tipo_vinculo),                             B_INTEGER,        32),     
                   'p_entrada_manha'             =>array(tvl($p_entrada_manha),                            B_VARCHAR,         5),
                   'p_saida_manha'               =>array(tvl($p_saida_manha),                              B_VARCHAR,         5),
                   'p_entrada_tarde'             =>array(tvl($p_entrada_tarde),                            B_VARCHAR,         5),
                   'p_saida_tarde'               =>array(tvl($p_saida_tarde),                              B_VARCHAR,         5),
                   'p_entrada_noite'             =>array(tvl($p_entrada_noite),                            B_VARCHAR,         5),
                   'p_saida_noite'               =>array(tvl($p_saida_noite),                              B_VARCHAR,         5),
                   'p_sabado'                    =>array($p_sabado,                                        B_VARCHAR,         1),
                   'p_domingo'                   =>array($p_domingo,                                       B_VARCHAR,         1),
                   'p_banco_horas_saldo'         =>array(tvl($p_banco_horas_saldo),                        B_VARCHAR,         8),     
                   'p_banco_horas_data'          =>array(tvl($p_banco_horas_data),                         B_DATE,           32),
                   'p_remuneracao_inicial'       =>array(toNumber(tvl($p_remuneracao_inicial)),            B_NUMERIC,      18,2),
                   'p_seguro_saude'              =>array($p_seguro_saude,                                  B_VARCHAR,         1),
                   'p_seguro_odonto'             =>array($p_seguro_odonto,                                 B_VARCHAR,         1),
                   'p_seguro_vida'               =>array($p_seguro_vida,                                   B_VARCHAR,         1),
                   'p_plano_saude'               =>array(tvl($p_plano_saude),                              B_VARCHAR,        30),
                   'p_plano_odonto'              =>array(tvl($p_plano_odonto),                             B_VARCHAR,        30),
                   'p_plano_vida'                =>array(tvl($p_plano_vida),                               B_VARCHAR,        30),
                   'p_vale_refeicao'             =>array(tvl($p_vale_refeicao),                            B_VARCHAR,         1),
                   'p_vale_transporte'           =>array(tvl($p_vale_transporte),                          B_VARCHAR,         1),
                   'p_observacao_beneficios'     =>array(tvl($p_observacao_beneficios),                    B_VARCHAR,      2000),
                   'p_data_atestado'             =>array($p_data_atestado,                                 B_DATE,           32),
                   'p_dias_experiencia'          =>array($p_dias_experiencia,                              B_INTEGER,        32)
                  );
                  //print_r($params);exit();
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
