<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getSiwCliData
*
* { Description :- 
*    Recupera os dados de um cliente da SIW a partir de seu CNPJ
* }
*/

class db_getSiwCliData {
   function getInstanceOf($dbms, $p_cnpj) {
     $sql='sp_getSiwCliData';
     $params=array("p_cnpj"     =>array($p_cnpj,        B_VARCHAR,   18),
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
