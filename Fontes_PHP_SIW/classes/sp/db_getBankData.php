<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getBankData
*
* { Description :- 
*    Recupera os dados do banco
* }
*/

class db_getBankData {
   function getInstanceOf($dbms, $p_chave) {
     $sql='sp_getBankData';
     $params=array("p_chave"    =>array($p_chave,       B_NUMERIC,   32),
                   "p_result"   =>array(null,           B_CURSOR,    -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        if ($l_rs = $l_rs->getResultArray()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}    
?>
