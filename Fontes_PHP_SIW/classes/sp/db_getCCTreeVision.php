<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getCCTreeVision
*
* { Description :- 
*    Recupera os centros de custo permitidos ao usuário informado
* }
*/

class db_getCCTreeVision {
   function getInstanceOf($dbms, $p_cliente, $p_pessoa, $p_menu, $p_restricao) {
     $sql='sp_getCCTreeVision';
     $params=array("p_cliente"   =>array($p_cliente,     B_NUMERIC,   32),
                   "p_pessoa"    =>array($p_pessoa,      B_NUMERIC,   32),
                   "p_menu"      =>array($p_menu,        B_NUMERIC,   32),
                   "p_restricao" =>array($p_restricao,   B_VARCHAR,   10),
                   "p_result"    =>array(null,           B_CURSOR,    -1)
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
