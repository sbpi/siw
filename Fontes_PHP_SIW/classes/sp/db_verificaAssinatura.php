<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_verificaAssinatura
*
* { Description :- 
*    Verifica se o usu�rio existe, se est� ativo e se a assinatura eletr�nica est� correta.
* }
*/

class db_verificaAssinatura {
   function getInstanceOf($dbms, $p_cliente, $p_username, $p_senha) {
     $sql='sp_verificaAssinat';
     $params=array("p_cliente"  =>array($p_cliente,     B_NUMERIC,   null),
                   "p_username" =>array($p_username,    B_VARCHAR,     30),
                   "p_senha"    =>array($p_senha,       B_VARCHAR,    255),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        $l_data = $l_rs->getResultArray();
        if     ($l_rs->getNumRows()==0) { return 2; }
        elseif (f($l_data,"ativo") == 'N') { return 3; }
        else   { return 0; }
     }
   }
}    
?>
