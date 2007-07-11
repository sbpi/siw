<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
include_once($w_dir_volta.'classes/db/DatabaseQueries.php');
/**
* class db_exec
*
* { Description :- 
*    Executa comandos SQL no banco de dados
* }
*/

class db_exec {
   function getInstanceOf($dbms, $p_sql, $numRows) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $l_rs = DatabaseQueriesFactory::getInstanceOf($p_sql, $dbms, null, DB_TYPE);
     if($l_rs->executeQuery()) { 
       $numRows  = $l_rs->getNumRows();
       if ($l_rs = $l_rs->getResultData()) {
         return $l_rs; 
       } else {
         return array();
       }
     }
   }
}
?>
