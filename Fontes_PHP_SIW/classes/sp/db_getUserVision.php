<?
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class db_getUserVision
*
* { Description :- 
*    Recupera os centros de custo que a pessoa tem visão geral
* }
*/

class db_getUserVision {
   function getInstanceOf($dbms, $p_menu, $p_chave) {
     $sql=$strschema.'sp_getUserVision';
     $params=array("p_menu"     =>array($p_menu,         B_NUMERIC,   32),
                   "p_chave"    =>array($p_chave,        B_NUMERIC,   32),
                   "p_result"   =>array(null,           B_CURSOR,    -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
