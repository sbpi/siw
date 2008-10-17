<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getCadastrador_CL
*
* { Description :- 
*    a solicitação pertence
* }
*/

class db_getCadastrador_CL {
   function getInstanceOf($dbms, $p_menu, $p_usuario) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql='FUNCTION'.$strschema.'CL_CADASTRADOR_GERAL';
     $params=array('p_menu'                      =>array($p_menu,                                          B_INTEGER,        32),
                   'p_usuario'                   =>array($p_usuario,                                       B_INTEGER,        32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
       error_reporting($l_error_reporting); 
        if ($l_rs = $l_rs->getResultArray()) {
          foreach($l_rs as $k => $v) return $v;
        } else {
          return 0;
        }
     }
   }
}
?>
