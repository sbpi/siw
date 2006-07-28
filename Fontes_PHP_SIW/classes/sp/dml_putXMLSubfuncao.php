<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLSubfuncao
*
* { Description :- 
*    Mantém a tabela PPA - Subfunção
* }
*/

class dml_putXMLSubfuncao {
   function getInstanceOf($dbms, $p_resultado, $p_chave, $p_funcao, $p_desc) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLSUBFUNCAO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         3),
                   'p_funcao'                    =>array(tvl($p_funcao),                                   B_VARCHAR,         2),
                   'p_desc'                      =>array(tvl($p_desc),                                     B_VARCHAR,       120)
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
