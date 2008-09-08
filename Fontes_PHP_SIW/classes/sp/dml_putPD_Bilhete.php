<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Bilhete
*
* { Description :- 
*    Grava bilhete de viagem
* }
*/

class dml_putPD_Bilhete {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_sq_cia_transporte, $p_data, $p_numero, 
        $p_trecho, $p_rloc, $p_classe, $p_valor_bilhete, $p_valor_taxa, $p_valor_pta, $p_deslocamento) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_Bilhete';
     $params=array('p_operacao'              =>array($operacao,                           B_VARCHAR,         1),
                   'p_chave'                 =>array(tvl($p_chave),                       B_INTEGER,        32),
                   'p_chave_aux'             =>array(tvl($p_chave_aux),                   B_INTEGER,        32),
                   'p_sq_cia_transporte'     =>array(tvl($p_sq_cia_transporte),           B_INTEGER,        32),
                   'p_data'                  =>array(tvl($p_data),                        B_DATE,           32),
                   'p_numero'                =>array(tvl($p_numero),                      B_VARCHAR,        20),
                   'p_trecho'                =>array(tvl($p_trecho),                      B_VARCHAR,        60),
                   'p_rloc'                  =>array(tvl($p_rloc),                        B_VARCHAR,         6),
                   'p_classe'                =>array(tvl($p_classe),                      B_VARCHAR,         5),
                   'p_valor_bilhete'         =>array(toNumber(tvl($p_valor_bilhete)),     B_NUMERIC,      18,2),
                   'p_valor_taxa'            =>array(toNumber(tvl($p_valor_taxa)),        B_NUMERIC,      18,2),
                   'p_valor_pta'             =>array(toNumber(tvl($p_valor_pta)),         B_NUMERIC,      18,2),
                   'p_deslocamento'          =>array(tvl($p_deslocamento),                B_VARCHAR,       200)
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
