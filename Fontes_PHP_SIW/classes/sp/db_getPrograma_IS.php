<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getPrograma_IS
*
* { Description :- 
*    Verifica se o programa já foi cadastrado
* }
*/

class db_getPrograma_IS {
   function getInstanceOf($dbms, $p_cd_programa, $ano, $cliente, $restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_GETPROGRAMA_IS';
     $params=array('p_cd_programa'               =>array($p_cd_programa,                                   B_VARCHAR,         4),
                   'p_ano'                       =>array($ano,                                             B_INTEGER,        32),
                   'p_cliente'                   =>array($cliente,                                         B_INTEGER,        32),
                   'p_restricao'                 =>array(tvl($restricao),                                  B_VARCHAR,        30),
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
