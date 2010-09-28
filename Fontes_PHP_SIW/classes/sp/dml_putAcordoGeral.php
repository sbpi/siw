<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoGeral
*
* { Description :- 
*    Grava a tela de dados gerais de um acordo
* }
*/

class dml_putAcordoGeral {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_menu, $p_unid_resp, $p_solicitante, $p_cadastrador, $p_sqcc, 
        $p_descricao, $p_justificativa, $p_inicio, $p_fim, $p_valor, $p_data_hora, $p_aviso, $p_dias, $p_cidade, $p_projeto, 
        $p_sq_tipo_acordo, $p_objeto, $p_sq_tipo_pessoa, $p_sq_forma_pagamento, $p_forma_atual, $p_inicio_atual, $p_etapa,
        $p_codigo, $p_titulo, $p_numero_empenho, $p_numero_processo, $p_data_assinatura,  $p_data_publicacao,
        $p_chave_nova, $p_copia, $p_herda, $p_codigo_interno) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putAcordoGeral';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_copia'                     =>array(tvl($p_copia),                                    B_INTEGER,        32),
                   'p_herda'                     =>array(tvl($p_herda),                                    B_VARCHAR,        40),
                   'p_menu'                      =>array($p_menu,                                          B_INTEGER,        32),
                   'p_unid_resp'                 =>array(tvl($p_unid_resp),                                B_INTEGER,        32),
                   'p_solicitante'               =>array(tvl($p_solicitante),                              B_INTEGER,        32),
                   'p_cadastrador'               =>array(tvl($p_cadastrador),                              B_INTEGER,        32),
                   'p_sqcc'                      =>array(tvl($p_sqcc),                                     B_INTEGER,        32),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      2000),
                   'p_justificativa'             =>array(tvl($p_justificativa),                            B_VARCHAR,      2000),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_valor'                     =>array(toNumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_data_hora'                 =>array(tvl($p_data_hora),                                B_VARCHAR,         1),
                   'p_aviso'                     =>array(tvl($p_aviso),                                    B_VARCHAR,         1),
                   'p_dias'                      =>array(nvl($p_dias,0),                                   B_INTEGER,        32),
                   'p_cidade'                    =>array(tvl($p_cidade),                                   B_INTEGER,        32),
                   'p_projeto'                   =>array(tvl($p_projeto),                                  B_INTEGER,        32),
                   'p_sq_tipo_acordo'            =>array(tvl($p_sq_tipo_acordo),                           B_INTEGER,        32),
                   'p_objeto'                    =>array(tvl($p_objeto),                                   B_VARCHAR,      2000),
                   'p_sq_tipo_pessoa'            =>array(tvl($p_sq_tipo_pessoa),                           B_INTEGER,        32),
                   'p_sq_forma_pagamento'        =>array(tvl($p_sq_forma_pagamento),                       B_INTEGER,        32),
                   'p_forma_atual'               =>array(tvl($p_forma_atual),                              B_INTEGER,        32),
                   'p_inicio_atual'              =>array(tvl($p_inicio_atual),                             B_DATE,           32),
                   'p_etapa'                     =>array(tvl($p_etapa),                                    B_INTEGER,        32),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,        60),
                   'p_titulo'                    =>array(tvl($p_titulo),                                   B_VARCHAR,        100),
                   'p_numero_empenho'            =>array(tvl($p_numero_empenho),                           B_VARCHAR,        30),
                   'p_numero_processo'           =>array(tvl($p_numero_processo),                          B_VARCHAR,        30),
                   'p_data_assinatura'           =>array(tvl($p_data_assinatura),                          B_DATE,           32),
                   'p_data_publicacao'           =>array(tvl($p_data_publicacao),                          B_DATE,           32),
                   'p_chave_nova'                =>array(&$p_chave_nova,                                   B_INTEGER,        32),
                   'p_codigo_interno'            =>array(&$p_codigo_interno,                               B_VARCHAR,        60)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
