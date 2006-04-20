<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getMenuList
*
* { Description :- 
*    Recupera os links aos quais uma opção pode ser subordinada
* }
*/

class db_getMenuList {
   function getInstanceOf($dbms, $p_cliente, $p_operacao, $p_chave) {
     $sql='sp_getMenuList';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,   32),
                   "p_operacao" =>array($p_operacao,    B_VARCHAR,    1),
                   "p_chave"    =>array($p_chave,       B_NUMERIC,   32),
                   "p_result"   =>array(null,           B_CURSOR,    -1)
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
