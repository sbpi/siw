<?php
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class db_getLinkDataParents
*
* { Description :- 
*    Recupera os dados dos pais do link informado
* }
*/

class db_getLinkDataParents {
   function getInstanceOf($dbms, $p_chave) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getLnkDataPrnts';
     $params=array("p_chave"    =>array($p_chave,       B_NUMERIC,     32),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
     if(!$l_rs->executeQuery()) {
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
     } else {
       error_reporting($l_error_reporting); 
        if ($l_rs = $l_rs->getResultData()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}    
?>
