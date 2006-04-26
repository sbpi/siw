<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getDeficiencyList
*
* { Description :- 
*    Recupera os grupos de deficiência existentes
* }
*/

class db_getDeficiencyList {
   function getInstanceOf($dbms, $p_nome, $p_ativo) {
     $sql='sp_getDefList';
     $params=array("p_nome"         =>array($p_nome,        B_VARCHAR,     50),
                   "p_ativo"        =>array($p_ativo,       B_VARCHAR,      1),
                   "p_result"       =>array(null,           B_CURSOR,      -1)
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
