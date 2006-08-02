<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Missao
*
* { Description :- 
*    Atualiza os dados financeiros em PD_MISSAO
* }
*/

class dml_putPD_Missao {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_valor_alimentacao, $p_valor_transporte, $p_valor_adicional, $p_desconto_alimentacao, $p_desconto_transporte, $p_pta, $p_emissao_bilhete, $p_valor_passagem, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTPDMISSAO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_valor_alimentacao'         =>array(toNumber(tvl($p_valor_alimentacao)),              B_NUMERIC,      18,2),
                   'p_valor_transporte'          =>array(toNumber(tvl($p_valor_transporte)),               B_NUMERIC,      18,2),
                   'p_valor_adicional'           =>array(toNumber(tvl($p_valor_adicional)),                B_NUMERIC,      18,2),
                   'p_desconto_alimentacao'      =>array(toNumber(tvl($p_desconto_alimentacao)),           B_NUMERIC,      18,2),
                   'p_desconto_transporte'       =>array(toNumber(tvl($p_desconto_transporte)),            B_NUMERIC,      18,2),
                   'p_pta'                       =>array(tvl($p_pta),                                      B_VARCHAR,        30),
                   'p_emissao_bilhete'           =>array(tvl($p_emissao_bilhete),                          B_DATE,           32),
                   'p_valor_passagem'            =>array(toNumber(tvl($p_valor_passagem)),                 B_NUMERIC,      18,2),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        30)
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
