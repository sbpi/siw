<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPDParametro
*
* { Description :- 
*    Mantém a tabela de parâmetros do módulo de viagens
* }
*/

class dml_putPDParametro {
   function getInstanceOf($dbms, $p_cliente, $p_sequencial, $p_ano_corrente, $p_prefixo, $p_sufixo, $p_antecedencia, 
              $p_prest_contas, $p_limite_unidade) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPDParametro';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_sequencial'                =>array(tvl($p_sequencial),                               B_INTEGER,        32),
                   'p_ano_corrente'              =>array(tvl($p_ano_corrente),                             B_INTEGER,        32),
                   'p_prefixo'                   =>array(tvl($p_prefixo),                                  B_VARCHAR,        10),
                   'p_sufixo'                    =>array(tvl($p_sufixo),                                   B_VARCHAR,        10),
                   'p_antecedencia'              =>array(tvl($p_antecedencia),                             B_INTEGER,        32),
                   'p_prest_contas'              =>array(tvl($p_prest_contas),                             B_INTEGER,        32),
                   'p_limite_unidade'            =>array(tvl($p_limite_unidade),                           B_VARCHAR,         1)
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
