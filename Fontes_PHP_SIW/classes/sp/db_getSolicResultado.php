<?
extract($GLOBALS); 
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getSolicResultado
*
* { Description :- 
*    Recupera os resultados
* }
*/

class db_getSolicResultado {
   function getInstanceOf($dbms, $p_programa, $p_projeto, $p_unidade, $p_solicitante, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getSolicResultado';
     $params=array('p_programa'                  =>array(tvl($p_programa),                                 B_INTEGER,        32),
                   'p_projeto'                   =>array(tvl($p_programa),                                 B_INTEGER,        32),
                   'p_unidade'                   =>array(tvl($p_programa),                                 B_INTEGER,        32),                   
                   'p_solicitante'               =>array(tvl($p_programa),                                 B_INTEGER,        32),                   
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,       200),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
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
