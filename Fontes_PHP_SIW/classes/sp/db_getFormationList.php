<?php
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getFormationList
*
* { Description :- 
*    Recupera as formações existentes
* }
*/

class db_getFormationList {
   function getInstanceOf($dbms, $p_tipo, $p_nome, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getFormatList';
     $params=array("p_tipo"         =>array($p_tipo,        B_VARCHAR,     20),
                   "p_nome"         =>array($p_nome,        B_VARCHAR,     50),
                   "p_ativo"        =>array($p_ativo,       B_VARCHAR,      1),
                   "p_result"       =>array(null,           B_CURSOR,      -1)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
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
