<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAcordoParc
*
* { Description :- 
*    Grava a tela de parcelas
* }
*/

class dml_putAcordoParc {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_ordem, $p_data, $p_valor, $p_observacao, $p_tipo_geracao, $p_vencimento, $p_dia_vencimento, $p_valor_parcela, $p_valor_diferente) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTACORDOPARC';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_ordem'                     =>array(tvl($p_ordem),                                    B_INTEGER,        32),
                   'p_data'                      =>array(tvl($p_data),                                     B_DATE,           32),
                   'p_valor'                     =>array(toNumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,       200),
                   'p_tipo_geracao'              =>array(tvl($p_tipo_geracao),                             B_INTEGER,        32),
                   'p_vencimento'                =>array(tvl($p_vencimento),                               B_VARCHAR,         1),
                   'p_dia_vencimento'            =>array(tvl($p_dia_vencimento),                           B_INTEGER,        32),
                   'p_valor_parcela'             =>array(tvl($p_valor_parcela),                            B_VARCHAR,         1),
                   'p_valor_diferente'           =>array(toNumber(tvl($p_valor_diferente)),                B_NUMERIC,      18,2)
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
