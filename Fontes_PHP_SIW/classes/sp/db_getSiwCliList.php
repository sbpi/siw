<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getSiwCliList
*
* { Description :- 
*    Recupera a lista de clientes da SIW
* }
*/

class db_getSiwCliList {
   function getInstanceOf($dbms, $p_pais, $p_uf, $p_cidade, $p_ativo, $p_nome) {
     $sql='sp_getSiwCliList';
     $params=array("p_pais"     =>array($p_pais,    B_NUMERIC,     32),
                   "p_uf"       =>array($p_uf,      B_VARCHAR,      3),
                   "p_cidade"   =>array($p_cidade,  B_NUMERIC,     32),
                   "p_ativo"    =>array($p_ativo,   B_VARCHAR,      1),
                   "p_nome"     =>array($p_nome,    B_VARCHAR,     60),
                   "p_result"   =>array(null,       B_CURSOR,      -1)
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
