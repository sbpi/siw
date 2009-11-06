<?
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getGPFolhaPontoDiario
*
* { Description :- 
*    Recupera as folhas de ponto baseado no contrato e mês
* }
*/

class db_getGPFolhaPontoDiario {
   function getInstanceOf($dbms, $p_contrato, $p_mes) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); 
     $sql=$strschema.'sp_getGPFolhaPontoDiario';
     $params=array("p_contrato"   =>array($p_contrato,      B_INTEGER,        32),
                   "p_mes"        =>array($p_mes,           B_VARCHAR,         6),
                   "p_result"     =>array(null,             B_CURSOR,         -1)
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
