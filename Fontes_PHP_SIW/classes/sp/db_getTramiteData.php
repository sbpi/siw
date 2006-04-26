<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getTramiteData
*
* { Description :- 
*    Recupera os dados de um trâmite
* }
*/

class db_getTramiteData {
   function getInstanceOf($dbms, $p_chave) {
     $sql='sp_getTramiteData';
     $params=array("p_chave"    =>array($p_chave,       B_NUMERIC,     32),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
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
