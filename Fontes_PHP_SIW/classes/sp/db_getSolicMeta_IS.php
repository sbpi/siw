<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getSolicMeta_IS
*
* { Description :- 
*    Recupera as metas de uma açao
* }
*/

class db_getSolicMeta_IS {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_restricao, $p_ano, $p_unidade, $p_cd_programa, $p_cd_acao, $p_preenchida, $p_meta_ppa, $p_exequivel) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_GETSOLICMETA_IS';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        20),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_unidade'                   =>array(tvl($p_unidade),                                  B_INTEGER,        32),
                   'p_cd_programa'               =>array(tvl($p_cd_programa),                              B_VARCHAR,         4),
                   'p_cd_acao'                   =>array(tvl($p_cd_acao),                                  B_VARCHAR,         4),
                   'p_preenchida'                =>array(tvl($p_preenchida),                               B_VARCHAR,         1),
                   'p_meta_ppa'                  =>array(tvl($p_meta_ppa),                                 B_VARCHAR,         1),
                   'p_exequivel'                 =>array(tvl($p_exequivel),                                B_VARCHAR,         1),
                   'p_programada'                =>array(tvl($p_programada),                               B_VARCHAR,         1),
                   'p_atraso'                    =>array(tvl($p_atraso),                                   B_VARCHAR,         1),
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
