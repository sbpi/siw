<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getIdiomList
*
* { Description :- 
*    Recupera os idiomas existentes
* }
*/

class db_getIdiomList {
   function getInstanceOf($dbms, $p_nome, $p_ativo) {
     $sql='sp_getIdiomList';
     $params=array("p_nome"         =>array($p_nome,        B_VARCHAR,     20),
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
