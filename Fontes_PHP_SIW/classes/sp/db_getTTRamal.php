<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getTTRamal
*
* { Description :- 
*    Recupera Eventos de Trigger
* }
*/

class db_getTTRamal {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_sq_central_fone, $p_codigo, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETTTRAMAL';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                             B_INTEGER,        18),
                   'p_chave'                     =>array(tvl($p_chave),                               B_INTEGER,        18),
                   'p_sq_central_fone'           =>array(tvl($p_sq_central_fone),                     B_INTEGER,        18),
                   'p_codigo'                    =>array(tvl($p_codigo),                              B_VARCHAR,         4),
                   'p_restricao'                 =>array(tvl($p_restricao),                           B_VARCHAR,         4),
                   'p_result'                    =>array(null,                                        B_CURSOR,         -1)
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
