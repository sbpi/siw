<?
include_once('classes/db/DatabaseQueriesFactory.php');
/**
* class db_getRegionList
*
* { Description :- 
*    Recupera as regiões existentes
* }
*/

class db_getRegionList {
   function getInstanceOf($dbms, $p_sq_pais, $p_tipo, $p_nome) {
     $sql='sp_getRegionList';
     $params=array('p_sq_pais'  =>array($p_sq_pais,       B_NUMERIC,   32),
                   'p_tipo'     =>array($p_tipo,          B_VARCHAR,    1),
                   'p_nome'     =>array($p_nome,          B_VARCHAR,   20),
                   'p_result'   =>array(null,             B_CURSOR,    -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     if(!$l_rs->executeQuery()) { die('Cannot query'); }
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
