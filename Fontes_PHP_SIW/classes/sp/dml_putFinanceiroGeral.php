<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putFinanceiroGeral
*
* { Description :- 
*    Grava a tela de dados gerais de um lançamento financeiro
* }
*/

class dml_putFinanceiroGeral {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_menu, $p_unidade, $p_solicitante, $p_cadastrador, $p_sqcc, 
          $p_descricao, $p_vencimento, $p_valor, $p_data_hora, $p_aviso, $p_dias, $p_cidade, $p_projeto, $p_sq_acordo_parcela, 
          $p_observacao, $p_sq_tipo_lancamento, $p_sq_forma_pagamento, $p_sq_tipo_pessoa, $p_forma_atual, $p_vencimento_atual, 
          $p_tipo_rubrica, $p_numero_processo, $p_per_ini, $p_per_fim, $p_condicao, $p_vinculo, $p_chave_nova, $p_codigo_interno) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putFinanceiroGeral';
     $params=array('p_operacao'                 =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                  =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_chave'                    =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_menu'                     =>array($p_menu,                                          B_INTEGER,        32),
                   'p_unidade'                  =>array(tvl($p_unidade),                                  B_INTEGER,        32),
                   'p_solicitante'              =>array(tvl($p_solicitante),                              B_INTEGER,        32),
                   'p_cadastrador'              =>array(tvl($p_cadastrador),                              B_INTEGER,        32),
                   'p_sqcc'                     =>array(tvl($p_sqcc),                                     B_INTEGER,        32),
                   'p_descricao'                =>array(tvl($p_descricao),                                B_VARCHAR,      2000),
                   'p_vencimento'               =>array(tvl($p_vencimento),                               B_DATE,           32),
                   'p_valor'                    =>array(tonumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_data_hora'                =>array(tvl($p_data_hora),                                B_VARCHAR,         1),
                   'p_aviso'                    =>array(tvl($p_aviso),                                    B_VARCHAR,         1),
                   'p_dias'                     =>array(nvl($p_dias,0),                                   B_INTEGER,        32),
                   'p_cidade'                   =>array(tvl($p_cidade),                                   B_INTEGER,        32),
                   'p_projeto'                  =>array(tvl($p_projeto),                                  B_INTEGER,        32),
                   'p_sq_acordo_parcela'        =>array(tvl($p_sq_acordo_parcela),                        B_INTEGER,        32),
                   'p_observacao'               =>array(tvl($p_observacao),                               B_VARCHAR,      2000),
                   'p_sq_tipo_lancamento'       =>array(tvl($p_sq_tipo_lancamento),                       B_INTEGER,        32),
                   'p_sq_forma_pagamento'       =>array(tvl($p_sq_forma_pagamento),                       B_INTEGER,        32),
                   'p_sq_tipo_pessoa'           =>array(tvl($p_sq_tipo_pessoa),                           B_INTEGER,        32),
                   'p_forma_atual'              =>array(tvl($p_forma_atual),                              B_INTEGER,        32),
                   'p_vencimento_atual'         =>array(tvl($p_vencimento_atual),                         B_DATE,           32),
                   'p_tipo_rubrica'             =>array(tvl($p_tipo_rubrica),                             B_INTEGER,        32),
                   'p_numero_processo'          =>array(tvl($p_numero_processo),                          B_VARCHAR,        30),
                   'p_per_ini'                  =>array(tvl($p_per_ini),                                  B_DATE,           32),
                   'p_per_fim'                  =>array(tvl($p_per_fim),                                  B_DATE,           32),
                   'p_condicao'                 =>array(tvl($p_condicao),                                 B_VARCHAR,      4000),
                   'p_vinculo'                  =>array(tvl($p_vinculo),                                  B_INTEGER,        32),
                   'p_chave_nova'               =>array(&$p_chave_nova,                                   B_INTEGER,        32),
                   'p_codigo_interno'           =>array(&$p_codigo_interno,                               B_VARCHAR,        60)
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
