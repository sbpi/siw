<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getVincKindData
*
* { Description :- 
*    Recupera os tipos de vínculos
* }
*/

class db_getVincKindData {
   function getInstanceOf($dbms, $p_sq_tipo_vinculo) {
     $sql='sp_getVincKindData';
     $params=array("p_sq_tipo_vinculo"  =>array($p_sq_tipo_vinculo, B_NUMERIC,     32),
                   "p_result"           =>array(null,               B_CURSOR,      -1)
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
