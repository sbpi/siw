<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getMenuOrder
*
* { Description :- 
*    Recupera o número de ordem das outras opções irmãs à informada
* }
*/

class db_getMenuOrder {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_chave_aux, $p_ultimo_nivel) {
     $sql='sp_getMenuOrder';
     $params=array("p_cliente"      =>array($p_cliente,          B_NUMERIC,   32),
                   "p_chave"        =>array($p_chave,            B_NUMERIC,   32),
                   "p_chave_aux"    =>array($p_chave_aux,        B_NUMERIC,   32),
                   "p_ultimo_nivel" =>array($p_ultimo_nivel,     B_VARCHAR,    1),
                   "p_result"       =>array(null,                B_CURSOR,    -1)
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
