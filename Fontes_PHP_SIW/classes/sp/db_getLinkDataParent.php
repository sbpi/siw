<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getLinkDataParent
*
* { Description :- 
*    Retorna as opções do menu concedidas para o usuário indicado.
* }
*/

class db_getLinkDataParent {
   function getInstanceOf($dbms, $p_cliente, $p_restricao) {
     $sql='SP_GetLnkDataPrnt';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,     32),
                   "p_restricao"=>array($p_restricao,   B_VARCHAR,     20),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        return $l_rs->getResultData();
     }
   }
}    
?>
