<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getLinkData
*
* { Description :- 
*    Retorna os dados de uma opção do menu.
* }
*/

class db_getLinkData {
   function getInstanceOf($dbms, $p_cliente, $p_sg) {
     $sql='sp_getLinkData';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,   null),
                   "p_sg"       =>array($p_sg,          B_VARCHAR,     50),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        return $l_rs->getResultArray();
     }
   }
}    
?>
