<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class db_getPersonData
*
* { Description :- 
*    Recupera os dados de uma pessoa cadastrada pelo cliente.
* }
*/

class db_getPersonData {
   function getInstanceOf($dbms, $p_cliente, $p_sq_pessoa, $p_cpf, $p_cnpj) {
     $sql='sp_getPersonData';
     $params=array("p_cliente"    =>array($p_cliente,     B_NUMERIC,   32),
                   "p_sq_pessoa"  =>array($p_sq_pessoa,   B_NUMERIC,   32),
                   "p_cpf"        =>array($p_cpf,         B_VARCHAR,   14),
                   "p_cnpj"       =>array($p_cnpj,        B_VARCHAR,   18),
                   "p_result"     =>array(null,           B_CURSOR,    -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die("Cannot query"); }
     else {
        return $l_rs->getResultArray();
     }
   }
}    
?>
