<?
include_once("classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getSegVincData
*
* { Description :- 
*    Recupera os dados do segmento
* }
*/

class db_getSegVincData {
   function getInstanceOf($dbms, $p_sigla, $p_sq_segmento, $p_nome, $p_sq_segmento_vinculo) {
     $sql='sp_getSegVincData';
     $params=array("p_sigla"                =>array($p_sigla,               B_VARCHAR,     30),
                   "p_sq_segmento"          =>array($p_sq_segmento,         B_NUMERIC,     32),
                   "p_nome"                 =>array($p_nome,                B_VARCHAR,     60),
                   "p_sq_segmento_vinculo"  =>array($p_sq_segmento_vinculo, B_NUMERIC,     32),
                   "p_result"               =>array(null,                   B_CURSOR,      -1)
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
