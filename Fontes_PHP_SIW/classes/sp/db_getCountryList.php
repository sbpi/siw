<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getCountryList
*
* { Description :- 
*    Recupera os países existentes.
* }
*/

class db_getCountryList {
   function getInstanceOf($dbms, $p_restricao, $p_nome, $p_ativo, $p_sigla) {
     $sql='sp_getCountryList';
     $params=array("p_restricao"=>array($p_restricao,   B_VARCHAR,     30),
                   "p_nome"     =>array($p_nome,        B_VARCHAR,     60),
                   "p_ativo"    =>array($p_ativo,       B_VARCHAR,      1),
                   "p_sigla"    =>array($p_sigla,       B_VARCHAR,      3),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        if ($l_rs = $l_rs->getResultData()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}    
?>
