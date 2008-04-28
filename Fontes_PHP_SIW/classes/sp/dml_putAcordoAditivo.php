<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoAditivo
*
* { Description :- 
*    Grava a tela de aditivos do acordo
* }
*/

class dml_putAcordoAditivo {
   function getInstanceOf($dbms, $operacao, $p_chave_aux, $p_chave, $p_protocolo, $p_codigo, $p_objeto, $p_inicio, $p_fim, $p_duracao, $p_doc_origem, $p_doc_data,
                          $p_variacao_valor, $p_prorrogacao, $p_revisao, $p_acrescimo, $p_supressao, $p_observacao, $p_valor_inicial, $p_parcela_inicial, $p_valor_reajuste, $p_parcela_reajustada, 
                          $p_valor_acrescimo, $p_parcela_acrescida, $p_sq_cc, $p_altera_item, $p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putAcordoAditivo';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_protocolo'                 =>array(tvl($p_protocolo),                                B_INTEGER,        32),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,        30),
                   'p_objeto'                    =>array(tvl($p_objeto),                                   B_VARCHAR,      2000),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),                   
                   'p_duracao'                   =>array(tvl($p_duracao),                                  B_INTEGER,        32),
                   'p_doc_origem'                =>array(tvl($p_doc_origem),                               B_VARCHAR,        30),
                   'p_doc_data'                  =>array(tvl($p_doc_data),                                 B_DATE,           32),
                   'p_variacao_valor'            =>array(toNumber(tvl($p_variacao_valor)),                 B_NUMERIC,      18,2),
                   'p_prorrogacao'               =>array(tvl($p_prorrogacao),                              B_VARCHAR,         1),
                   'p_revisao'                   =>array(tvl($p_revisao),                                  B_VARCHAR,         1),
                   'p_acrescimo'                 =>array(tvl($p_acrescimo),                                B_VARCHAR,         1),
                   'p_supressao'                 =>array(tvl($p_supressao),                                B_VARCHAR,         1),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,      2000),
                   'p_valor_inicial'             =>array(toNumber(tvl($p_valor_inicial)),                  B_NUMERIC,      18,2),
                   'p_parcela_inicial'           =>array(toNumber(tvl($p_parcela_inicial)),                B_NUMERIC,      18,2),
                   'p_valor_reajuste'            =>array(toNumber(tvl($p_valor_reajuste)),                 B_NUMERIC,      18,2),
                   'p_parcela_reajustada'        =>array(toNumber(tvl($p_parcela_reajustada)),             B_NUMERIC,      18,2),
                   'p_valor_acrescimo'           =>array(toNumber(tvl($p_valor_acrescimo)),                B_NUMERIC,      18,2),
                   'p_parcela_acrescida'         =>array(toNumber(tvl($p_parcela_acrescida)),              B_NUMERIC,      18,2),
                   'p_sq_cc'                     =>array(tvl($p_sq_cc),                                    B_INTEGER,        32),
                   'p_altera_item'               =>array(tvl($p_altera_item),                              B_VARCHAR,         1),
                   'p_chave_nova'                =>array(&$p_chave_nova,                                   B_INTEGER,        32)
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
