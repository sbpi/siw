<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCVExperiencia
*
* { Description :- 
*    Mantém os dados da experiência profissional do colaborador
* }
*/

class dml_putCVExperiencia {
   function getInstanceOf($dbms, $operacao, $p_pessoa, $p_chave, $p_sq_area_conhecimento, $p_sq_cidade, $p_sq_eo_tipo_posto, $p_sq_tipo_vinculo, $p_empregador, $p_entrada, $p_saida, $p_duracao_mes, $p_duracao_ano, $p_motivo_saida, $p_ultimo_salario, $p_atividades) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTCVEXP';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_pessoa'                    =>array($p_pessoa,                                        B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_area_conhecimento'      =>array($p_sq_area_conhecimento,                          B_INTEGER,        32),
                   'p_sq_cidade'                 =>array($p_sq_cidade,                                     B_INTEGER,        32),
                   'p_sq_eo_tipo_posto'          =>array(tvl($p_sq_eo_tipo_posto),                         B_INTEGER,        32),
                   'p_sq_tipo_vinculo'           =>array(tvl($p_sq_tipo_vinculo),                          B_INTEGER,        32),
                   'p_empregador'                =>array($p_empregador,                                    B_VARCHAR,        60),
                   'p_entrada'                   =>array($p_entrada,                                       B_DATE,           32),
                   'p_saida'                     =>array(tvl($p_saida),                                    B_DATE,           32),
                   'p_duracao_mes'               =>array(tvl($p_duracao_mes),                              B_INTEGER,        32),
                   'p_duracao_ano'               =>array(tvl($p_duracao_ano),                              B_INTEGER,        32),
                   'p_motivo_saida'              =>array(tvl($p_motivo_saida),                             B_VARCHAR,       255),
                   'p_ultimo_salario'            =>array(toNumber(tvl($p_ultimo_salario)),                 B_NUMERIC,      12,2),
                   'p_atividades'                =>array(tvl($p_atividades),                               B_VARCHAR,      4000)
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
