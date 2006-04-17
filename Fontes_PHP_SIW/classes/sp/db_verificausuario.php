<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_verificaUsuario
*
* { Description :- 
*    Verifica se o usuário existe e se está ativo.
* }
*/

class db_verificaUsuario {
   function getInstanceOf($dbms, $p_cliente, $p_username) {
     $sql='sp_verificaUsuario';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,   null),
                   "p_username" =>array($p_username,    B_VARCHAR,     30),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        if ($l_rs = $l_rs->getResultArray()) {
          return f($l_rs,"existe");
        } else {
          return 'N';
        }
     }
   }
}    
?>
