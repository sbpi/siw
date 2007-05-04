<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putLancamentoDoc
*
* { Description :- 
*    Grava a tela de documentos
* }
*/

class dml_putLancamentoDoc {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_sq_tipo_documento, $p_numero, $p_data, $p_serie, $p_valor, $p_patrimonio, $p_retencao, $p_tributo, $p_nota, $p_inicial, $p_excedente, $p_reajuste, $p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTLANCAMENTODOC';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_sq_tipo_documento'         =>array(tvl($p_sq_tipo_documento),                        B_INTEGER,        32),
                   'p_numero'                    =>array(tvl($p_numero),                                   B_VARCHAR,        30),
                   'p_data'                      =>array(tvl($p_data),                                     B_DATE,           32),
                   'p_serie'                     =>array(tvl($p_serie),                                    B_VARCHAR,        10),
                   'p_valor'                     =>array(tonumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_patrimonio'                =>array(tvl($p_patrimonio),                               B_VARCHAR,         1),
                   'p_retencao'                  =>array(tvl($p_retencao),                                 B_VARCHAR,         1),
                   'p_tributo'                   =>array(tvl($p_tributo),                                  B_VARCHAR,         1),
                   'p_nota'                      =>array(tvl($p_nota),                                     B_INTEGER,        32),
                   'p_inicial'                   =>array(tonumber(tvl($p_inicial)),                        B_NUMERIC,      18,2),
                   'p_excedente'                 =>array(tonumber(tvl($p_excedente)),                      B_NUMERIC,      18,2),
                   'p_reajuste'                  =>array(tonumber(tvl($p_reajuste)),                       B_NUMERIC,      18,2),
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
