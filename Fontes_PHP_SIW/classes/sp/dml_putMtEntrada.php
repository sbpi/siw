<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_puMtEntrada
*
* { Description :- 
*    Grava a tela inicial da entrada de material
* }
*/

class dml_putMtEntrada {
   function getInstanceOf($dbms, $p_operacao,$p_cliente,$p_usuario,$p_chave,$p_copia,$p_executor, $p_fornecedor,$p_tipo_movimentacao,$p_situacao,
           $p_solicitacao,$p_documento,$p_previsto,$p_efetivo,$p_tipo_doc,$p_numero_doc,$p_data_doc,$p_valor_doc,$p_armazenamento,
           $p_numero_empenho,$p_data_empenho, &$p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMtEntrada';
     $params=array('p_operacao'               =>array($p_operacao,                                B_VARCHAR,        30),
                   'p_cliente'                =>array(tvl($p_cliente),                            B_INTEGER,        32),
                   'p_usuario'                =>array(tvl($p_usuario),                            B_INTEGER,        32),
                   'p_chave'                  =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_copia'                  =>array(tvl($p_copia),                              B_INTEGER,        32),
                   'p_executor'               =>array(tvl($p_executor),                           B_INTEGER,        32),                   
                   'p_fornecedor'             =>array(tvl($p_fornecedor),                         B_INTEGER,        32),                   
                   'p_tipo_movimentacao'      =>array(tvl($p_tipo_movimentacao),                  B_INTEGER,        32),
                   'p_situacao'               =>array(tvl($p_situacao),                           B_INTEGER,        32),
                   'p_solicitacao'            =>array(tvl($p_solicitacao),                        B_INTEGER,        32),
                   'p_documento'              =>array(tvl($p_documento),                          B_INTEGER,        32),
                   'p_previsto'               =>array(tvl($p_previsto),                           B_DATE,           32),
                   'p_efetivo'                =>array(tvl($p_efetivo),                            B_DATE,           32),
                   'p_tipo_doc'               =>array(tvl($p_tipo_doc),                           B_INTEGER,        32),
                   'p_numero_doc'             =>array(tvl($p_numero_doc),                         B_VARCHAR,        60),
                   'p_data_doc'               =>array(tvl($p_data_doc),                           B_DATE,           32),
                   'p_valor_doc'              =>array(toNumber(tvl($p_valor_doc)),                B_NUMERIC,      18,2),
                   'p_armazenamento'          =>array(tvl($p_armazenamento),                      B_DATE,           32),
                   'p_numero_empenho'         =>array(tvl($p_numero_empenho),                     B_VARCHAR,        25),
                   'p_data_empenho'           =>array(tvl($p_data_empenho),                       B_DATE,           32),
                   'p_chave_nova'             =>array(&$p_chave_nova,                             B_INTEGER,        32)
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
