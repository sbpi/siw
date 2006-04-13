<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getUserData
*
* { Description :- 
*    Retorna os dados do usuário indicado.
* }
*/

class db_getUserData {
   function getInstanceOf($dbms, $p_cliente, $p_username) {
     $sql='sp_getUserData';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,   null),
                   "p_username" =>array($p_username,    B_VARCHAR,     30),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        $l_data = $l_rs->getResultArray();
        return $l_data;
     }
   }
}    
?>
