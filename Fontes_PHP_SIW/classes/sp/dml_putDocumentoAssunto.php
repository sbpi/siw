<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putDocumentoAssunto
*
* { Description :- 
*    Mantém a tabela de assuntos de um documento
* }
*/

class dml_putDocumentoAssunto {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_assunto, $p_principal) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putDocumentoAssunto';
     $params=array('p_operacao'        =>array($operacao,                     B_VARCHAR,         1),
                   'p_chave'           =>array($p_chave,                      B_INTEGER,        32),
                   'p_assunto'         =>array($p_assunto,                    B_INTEGER,        32),
                   'p_principal'       =>array(tvl($p_principal),             B_VARCHAR,         1)
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
