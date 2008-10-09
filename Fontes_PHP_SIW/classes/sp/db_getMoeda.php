<?
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class sp_getMoeda
*
* { Description :- 
*    Recupera as unidades monetárias existentes.
* }
*/

class db_getMoeda {
   function getInstanceOf($dbms, $p_chave, $p_restricao, $p_nome, $p_ativo, $p_sigla) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getMoeda';
     $params=array('p_chave'    =>array($p_chave,       B_INTEGER,     32),
                   'p_restricao'=>array($p_restricao,   B_VARCHAR,     30),
                   'p_nome'     =>array($p_nome,        B_VARCHAR,     60),
                   'p_ativo'    =>array($p_ativo,       B_VARCHAR,      1),
                   'p_sigla'    =>array($p_sigla,       B_VARCHAR,      3),
                   'p_result'   =>array(null,           B_CURSOR,      -1)
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