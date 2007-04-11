<?
extract($GLOBALS); include_once($w_dir_volta."classes/db/DatabaseQueriesFactory.php");
/**
* class db_getCTEspecificacao
*
* { Description :- 
*    Recupera as espcificações de despesa
* }
*/

class db_getCTEspecificacao {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_chave_pai, $p_ano, $p_ativo, $p_ultimo_nivel, $p_ctcc, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETCTESPECIFICACAO';
     $params=array("p_cliente"          =>array($p_cliente,             B_NUMERIC,   32),
                   "p_chave"            =>array($p_chave,               B_NUMERIC,   32),
                   "p_chave_pai"        =>array($p_chave_pai,           B_NUMERIC,   32),
                   "p_ano"              =>array($p_ano,                 B_VARCHAR,    4),
                   "p_ativo"            =>array($p_ativo,               B_VARCHAR,    1),
                   "p_ultimo_nivel"     =>array($p_ultimo_nivel,        B_VARCHAR,    1),
                   "p_ctcc"             =>array($p_ctcc,                B_NUMERIC,   32),
                   "p_restricao"        =>array($p_restricao,           B_VARCHAR,   20),
                   "p_result"           =>array(null,                   B_CURSOR,    -1)
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
