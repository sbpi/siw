<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getFinancAcaoPPA_IS
*
* { Description :- 
*    Recupera ações de financiamento de uma ação específica
* }
*/

class db_getFinancAcaoPPA_IS {
   function getInstanceOf($dbms, $p_chave, $p_cliente, $p_ano, $p_programa, $p_acao, $p_subacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_GETFINACACAOPPA_IS';
     $params=array('p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_ano'                       =>array($p_ano,                                           B_INTEGER,        32),
                   'p_cd_programa'               =>array(tvl($p_programa),                                 B_VARCHAR,         4),
                   'p_cd_acao'                   =>array(tvl($p_acao),                                     B_VARCHAR,         4),
                   'p_cd_subacao'                =>array(tvl($p_subacao),                                  B_VARCHAR,         4),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0);
     if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
       error_reporting($l_error_reporting); 
       if ($l_rs = $l_rs->getResultData()) {
         return $l_rs;
       } else {
         return array();
       }
     }
   }
}
?>
