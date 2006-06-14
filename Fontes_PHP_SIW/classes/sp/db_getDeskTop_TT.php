<?
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getDesktop_TT
*
* { Description :- 
*    Recupera a mesa de trabalho de um usuário, relativa a telefonemas.
* }
*/

class db_getDesktop_TT {
   function getInstanceOf($dbms, $p_usuario) {
     $sql=$strschema.'sp_getDesktop_TT';
     $params=array("p_usuario"  =>array($p_usuario,     B_NUMERIC,     32),
                   "p_result"   =>array(null,           B_CURSOR,      -1)
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
