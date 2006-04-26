<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getKindPersonList
*
* { Description :- 
*    Recupera os tipo de pessoas existentes
* }
*/

class db_getKindPersonList {
   function getInstanceOf($dbms, $p_nome) {
     $sql='sp_getKindPersList';
     $params=array("p_nome"     =>array($p_nome,    B_VARCHAR,     60),
                   "p_result"   =>array(null,       B_CURSOR,      -1)
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
