<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putFNParametro
*
* { Description :- 
*    Mantém a tabela de parâmetros do módulo financeiro
* }
*/

class dml_putFNParametro {
   function getInstanceOf($dbms, $p_cliente, $p_sequencial, $p_ano_corrente, $p_prefixo, $p_sufixo, $p_devolucao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTFNPARAMETRO';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_sequencial'                =>array(tvl($p_sequencial),                               B_INTEGER,        32),
                   'p_ano_corrente'              =>array(tvl($p_ano_corrente),                             B_INTEGER,        32),
                   'p_prefixo'                   =>array(tvl($p_prefixo),                                  B_VARCHAR,        10),
                   'p_sufixo'                    =>array(tvl($p_sufixo),                                   B_VARCHAR,        10),
                   'p_devolucao'                 =>array(tvl($p_devolucao),                                B_VARCHAR,      4000)                   
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
