<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putFinanceiroConc
*
* { Description :- 
*    Conclui o lançamento financeiro
* }
*/

class dml_putFinanceiroConc {
   function getInstanceOf($dbms, $p_menu, $p_chave, $p_pessoa, $p_tramite, $p_quitacao, $p_valor_real, $p_codigo_deposito, $p_conta,
          $p_tipo_lancamento, $p_rubrica, $p_observacao, $p_cc_debito, $p_cc_credito, $p_caminho, $p_tamanho, $p_tipo, $p_nome_original) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putFinanceiroConc';
     $params=array('p_menu'                      =>array($p_menu,                                          B_INTEGER,        32),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_pessoa'                    =>array($p_pessoa,                                        B_INTEGER,        32),
                   'p_tramite'                   =>array($p_tramite,                                       B_INTEGER,        32),
                   'p_quitacao'                  =>array($p_quitacao,                                      B_DATE,           32),
                   'p_valor_real'                =>array(tonumber(tvl($p_valor_real)),                     B_NUMERIC,      18,2),
                   'p_codigo_deposito'           =>array(tvl($p_codigo_deposito),                          B_VARCHAR,        50),
                   'p_conta'                     =>array($p_conta,                                         B_INTEGER,        32),
                   'p_tipo_lancamento'           =>array(tvl($p_tipo_lancamento),                          B_INTEGER,        32),
                   'p_rubrica'                   =>array(tvl($p_rubrica),                                  B_INTEGER,        32),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,       500),
                   'p_cc_debito'                 =>array(tvl($p_cc_debito),                                B_VARCHAR,        25),
                   'p_cc_credito'                =>array(tvl($p_cc_credito),                               B_VARCHAR,        25),
                   'p_caminho'                   =>array(tvl($p_caminho),                                  B_VARCHAR,       255),
                   'p_tamanho'                   =>array(tvl($p_tamanho),                                  B_INTEGER,        32),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_VARCHAR,       100),
                   'p_nome_original'             =>array(tvl($p_nome_original),                            B_VARCHAR,       255)
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
