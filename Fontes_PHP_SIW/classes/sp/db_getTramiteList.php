<?
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getTramiteList
*
* { Description :- 
*    Recupera os trâmites da opção de menu indicada.
* }
*/

class db_getTramiteList {
   function getInstanceOf($dbms, $p_chave, $p_restricao, $p_ativo) {
     $sql=$strschema.'sp_getTramiteList';
     $params=array("p_chave"    =>array($p_chave,       B_NUMERIC,     32),
                   "p_restricao"=>array($p_restricao,   B_VARCHAR,     20),
                   "p_ativo"    =>array($p_ativo,       B_VARCHAR,      1),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
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
