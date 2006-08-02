<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getCVList
*
* { Description :- 
*    Recupera informações consolidadas das solicitações
* }
*/

class db_getCVList {
   function getInstanceOf($dbms, $p_cliente, $p_sq_formacao, $p_sq_idioma, $p_sexo, $p_nome) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_GETCVLIST';
     $params=array('p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_sq_formacao'               =>array(tvl($p_sq_formacao),                              B_INTEGER,        32),
                   'p_sq_idioma'                 =>array(tvl($p_sq_idioma),                                B_INTEGER,        32),
                   'p_sexo'                      =>array(tvl($p_sexo),                                     B_VARCHAR,         1),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        40),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0);
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
