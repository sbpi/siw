<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getSegModList
*
* { Description :- 
*    Recupera a lista de módulos de um segmento escolhido
* }
*/

class db_getSegModList {
   function getInstanceOf($dbms, $p_chave) {
     $sql='sp_getSegModList';
     $params=array("p_chave"    =>array($p_chave,       B_NUMERIC,   32),
                   "p_result"   =>array(null,           B_CURSOR,    -1)
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
