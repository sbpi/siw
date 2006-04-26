<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getFormationList
*
* { Description :- 
*    Recupera as formações existentes
* }
*/

class db_getFormationList {
   function getInstanceOf($dbms, $p_tipo, $p_nome, $p_ativo) {
     $sql='sp_getFormatList';
     $params=array("p_tipo"         =>array($p_tipo,        B_VARCHAR,     20),
                   "p_nome"         =>array($p_nome,        B_VARCHAR,     50),
                   "p_ativo"        =>array($p_ativo,       B_VARCHAR,      1),
                   "p_result"       =>array(null,           B_CURSOR,      -1)
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
