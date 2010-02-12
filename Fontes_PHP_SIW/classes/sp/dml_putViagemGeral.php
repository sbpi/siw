<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putViagemGeral
*
* { Description :- 
*    Grava a tela de dados gerais de um acordo
* }
*/

class dml_putViagemGeral {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_menu, $p_unidade, $p_unid_resp, $p_solicitante, 
        $p_cadastrador, $p_tipo, $p_descricao, $p_agenda, $p_justificativa, $p_inicio, $p_fim, $p_data_hora, $p_aviso, 
        $p_dias, $p_projeto, $p_atividade, $p_inicio_atual, $p_passagem, $p_diaria, $p_hospedagem, $p_veiculo, 
        $p_proponente, $p_financeiro, $p_rubrica, $p_lancamento, $p_chave_nova, $p_copia, $p_codigo_interno) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putViagemGeral';
     $params=array('p_operacao'              =>array($operacao,                          B_VARCHAR,         1),
                   'p_cliente'               =>array($p_cliente,                         B_INTEGER,        32),
                   'p_chave'                 =>array(tvl($p_chave),                      B_INTEGER,        32),
                   'p_menu'                  =>array($p_menu,                            B_INTEGER,        32),
                   'p_unidade'               =>array(tvl($p_unidade),                    B_INTEGER,        32),
                   'p_unid_resp'             =>array(tvl($p_unid_resp),                  B_INTEGER,        32),
                   'p_solicitante'           =>array(tvl($p_solicitante),                B_INTEGER,        32),
                   'p_cadastrador'           =>array(tvl($p_cadastrador),                B_INTEGER,        32),
                   'p_tipo'                  =>array(tvl($p_tipo),                       B_VARCHAR,         1),
                   'p_descricao'             =>array(tvl($p_descricao),                  B_VARCHAR,      2000),
                   'p_agenda'                =>array(tvl($p_agenda),                     B_VARCHAR,      2000),
                   'p_justificativa'         =>array(tvl($p_justificativa),              B_VARCHAR,      2000),
                   'p_inicio'                =>array(tvl($p_inicio),                     B_DATE,           32),
                   'p_fim'                   =>array(tvl($p_fim),                        B_DATE,           32),
                   'p_data_hora'             =>array(tvl($p_data_hora),                  B_VARCHAR,         1),
                   'p_aviso'                 =>array(tvl($p_aviso),                      B_VARCHAR,         1),
                   'p_dias'                  =>array(nvl($p_dias,0),                     B_INTEGER,        32),
                   'p_projeto'               =>array(tvl($p_projeto),                    B_INTEGER,        32),
                   'p_atividade'             =>array(tvl($p_atividade),                  B_INTEGER,        32),
                   'p_inicio_atual'          =>array(tvl($p_inicio_atual),               B_DATE,           32),
                   'p_passagem'              =>array(tvl($p_passagem),                   B_VARCHAR,         1),
                   'p_diaria'                =>array(tvl($p_diaria),                     B_INTEGER,        32),
                   'p_hospedagem'            =>array(tvl($p_hospedagem),                 B_VARCHAR,         1),
                   'p_veiculo'               =>array(tvl($p_veiculo),                    B_VARCHAR,         1),
                   'p_proponente'            =>array(tvl($p_proponente),                 B_VARCHAR,        90),
                   'p_financeiro'            =>array(tvl($p_financeiro),                 B_INTEGER,        32),
                   'p_rubrica'               =>array(tvl($p_rubrica),                    B_INTEGER,        32),
                   'p_lancamento'            =>array(tvl($p_lancamento),                 B_INTEGER,        32),
                   'p_chave_nova'            =>array(&$p_chave_nova,                     B_INTEGER,        32),
                   'p_copia'                 =>array(tvl($p_copia),                      B_INTEGER,        32),
                   'p_codigo_interno'        =>array(&$p_codigo_interno,                 B_VARCHAR,        60)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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