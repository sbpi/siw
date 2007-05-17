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
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_aditivo, $p_ordem, $p_data, $p_valor, 
        $p_observacao, $p_tipo_geracao, $p_vencimento, $p_dia_vencimento, $p_valor_parcela, $p_valor_diferente, 
        $p_per_ini, $p_per_fim, $p_valor_inicial, $p_valor_excedente, $p_valor_reajuste) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTACORDOPARC';
     $params=array('p_operacao'                 =>array($operacao,                                  B_VARCHAR,         1),
                   'p_chave'                    =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_chave_aux'                =>array(tvl($p_chave_aux),                          B_INTEGER,        32),
                   'p_aditivo'                  =>array(tvl($p_aditivo),                            B_INTEGER,        32),
                   'p_ordem'                    =>array(tvl($p_ordem),                              B_INTEGER,        32),
                   'p_data'                     =>array(tvl($p_data),                               B_DATE,           32),
                   'p_valor'                    =>array(toNumber(tvl($p_valor)),                    B_NUMERIC,      18,2),
                   'p_observacao'               =>array(tvl($p_observacao),                         B_VARCHAR,       200),
                   'p_tipo_geracao'             =>array(tvl($p_tipo_geracao),                       B_INTEGER,        32),
                   'p_vencimento'               =>array(tvl($p_vencimento),                         B_VARCHAR,         1),
                   'p_dia_vencimento'           =>array(tvl($p_dia_vencimento),                     B_INTEGER,        32),
                   'p_valor_parcela'            =>array(tvl($p_valor_parcela),                      B_VARCHAR,         1),
                   'p_valor_diferente'          =>array(toNumber(tvl($p_valor_diferente)),          B_NUMERIC,      18,2),
                   'p_per_ini'                  =>array(tvl($p_per_ini),                            B_DATE,           32),
                   'p_per_fim'                  =>array(tvl($p_per_fim),                            B_DATE,           32),
                   'p_valor_inicial'            =>array(toNumber(tvl($p_valor_inicial)),          B_NUMERIC,      18,2),
                   'p_valor_excedente'          =>array(toNumber(tvl($p_valor_excedente)),        B_NUMERIC,      18,2),
                   'p_valor_reajuste'           =>array(toNumber(tvl($p_valor_reajuste)),         B_NUMERIC,      18,2)
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
