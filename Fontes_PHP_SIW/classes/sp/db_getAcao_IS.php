<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getAcao_IS
*
* { Description :- 
*    Verfica se a ação já foi cadastrada
* }
*/

class db_getAcao_IS {
   function getInstanceOf($dbms, $p_cd_programa, $p_cd_acao, $p_cd_unidade, $ano, $cliente, $restricao, $p_sq_isprojeto) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_GETACAO_IS';
     $params=array('p_cd_programa'               =>array(tvl($p_cd_programa),                              B_VARCHAR,         4),
                   'p_cd_acao'                   =>array(tvl($p_cd_acao),                                  B_VARCHAR,         4),
                   'p_cd_unidade'                =>array(tvl($p_cd_unidade),                               B_VARCHAR,         5),
                   'p_ano'                       =>array($ano,                                             B_INTEGER,        32),
                   'p_cliente'                   =>array($cliente,                                         B_INTEGER,        32),
                   'p_restricao'                 =>array(tvl($restricao),                                  B_VARCHAR,        30),
                   'p_sq_isprojeto'              =>array(tvl($p_sq_isprojeto),                             B_INTEGER,        32),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR);
     if(!$l_rs->executeQuery()) {
       error_reporting($l_error_reporting);
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
     } else {
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
