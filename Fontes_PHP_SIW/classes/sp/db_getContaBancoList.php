<?
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getContaBancoList
*
* { Description :- 
*    Recupera as contas bancárias de um cliente
* }
*/

class db_getContaBancoList {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_restricao) {
     $sql=$strschema.'sp_getBankAccList';
     $params=array("p_cliente"      =>array($p_cliente,     B_NUMERIC,     32),
                   "p_chave"        =>array($p_chave,       B_NUMERIC,     32),
                   "p_restricao"    =>array($p_restricao,   B_VARCHAR,     20),
                   "p_result"       =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
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
