<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putGPFerias
*
* { Description :- 
*    Mantém a tabela principal de solicitações, especificamente as de recursos logísticos.
* }
*/

class dml_putGPFerias {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_menu, $p_unidade, $p_solicitante, $p_cadastrador, 
        $p_descricao, $p_justificativa, $p_contrato_colaborador, $p_inicio, $p_inicio_periodo, $p_fim, $p_fim_periodo, 
        $p_inicio_aquisitivo, $p_fim_aquisitivo, $p_abono_pecuniario, $p_data_hora, $p_cidade, $p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putGPFerias';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_menu'                      =>array($p_menu,                                          B_INTEGER,        32),
                   'p_unidade'                   =>array(tvl($p_unidade),                                  B_INTEGER,        32),
                   'p_solicitante'               =>array(tvl($p_solicitante),                              B_INTEGER,        32),
                   'p_cadastrador'               =>array(tvl($p_cadastrador),                              B_INTEGER,        32),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      2000),
                   'p_justificativa'             =>array(tvl($p_justificativa),                            B_VARCHAR,      2000),
                   'p_contrato_colaborador'      =>array(tvl($p_contrato_colaborador),                     B_INTEGER,        32),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_inicio_periodo'            =>array(tvl($p_inicio_periodo),                           B_VARCHAR,         1),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_fim_periodo'               =>array(tvl($p_fim_periodo),                              B_VARCHAR,         1),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_inicio_aquisitivo'         =>array(tvl($p_inicio_aquisitivo),                        B_DATE,           32),
                   'p_fim_aquisitivo'            =>array(tvl($p_fim_aquisitivo),                           B_DATE,           32),
                   'p_abono_pecuniario'          =>array(tvl($p_abono_pecuniario),                         B_VARCHAR,         1),
                   'p_data_hora'                 =>array(tvl($p_data_hora),                                B_VARCHAR,         1),
                   'p_cidade'                    =>array(tvl($p_cidade),                                   B_INTEGER,        32),
                   'p_chave_nova'                =>array(&$p_chave_nova,                                   B_INTEGER,        32)
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
