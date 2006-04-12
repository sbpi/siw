<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getSegModData
*
* { Description :- 
*    Retorna os dados de um módulo da SIW.
* }
*/

class db_getSegModData {
   function getInstanceOf($dbms, $p_segmento, $p_modulo) {
     $sql='sp_getSegModData';
     $params=array("p_segmento" =>array($p_segmento,    B_NUMERIC,   32),
                   "p_modulo"   =>array($p_modulo,      B_NUMERIC,   32),
                   "p_result"   =>array(null,           B_CURSOR,    -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        return DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, $DB_TYPE);
     }
   }
}    
?>
