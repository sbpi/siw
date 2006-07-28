<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putXMLTipo_Situacao
*
* { Description :- 
*    Mant�m a tabela SIG - Tipo situa��o
* }
*/

class dml_putXMLTipo_Situacao {
   function getInstanceOf($dbms, $p_resultado, $p_chave, $p_nome, $p_tipo, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTXMLTIPO_SITUACAO';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_VARCHAR,         2),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        50),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_VARCHAR,         2),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
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
