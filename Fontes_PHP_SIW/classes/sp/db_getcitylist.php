<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getcitylist
*
* { Description :- 
*    This class returns an object with all selected cities
* }
*/

class db_getCityList {
   function getInstanceOf($dbms, $p_pais, $p_estado) {
     $sql='sp_getCityList';
     $params=array("p_pais"     =>array($p_pais,   B_NUMERIC,   null),
                   "p_estado"   =>array($p_estado, B_VARCHAR,      2),
                   "p_result"   =>array(null,      B_CURSOR,      -1)
                  );
     
     return DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, $DB_TYPE);
   }
}    
?>