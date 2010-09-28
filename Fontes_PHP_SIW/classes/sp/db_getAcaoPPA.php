<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getAcaoPPA
*
* { Description :- 
*    Recupera ações do ppa
* }
*/

class db_getAcaoPPA {
   function getInstanceOf($dbms, $p_chave, $p_cliente, $p_programa, $p_acao, $p_responsavel, $p_mpog, $p_relevante, $p_sq_siw_solicitacao, $p_cod_programa, $p_cod_acao, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_GETACAOPPA';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_programa'                  =>array(tvl($p_programa),                                 B_INTEGER,        32),
                   'p_acao'                      =>array(tvl($p_acao),                                     B_INTEGER,        32),
                   'p_responsavel'               =>array(tvl($p_responsavel),                              B_VARCHAR,        60),
                   'p_mpog'                      =>array(tvl($p_mpog),                                     B_VARCHAR,         1),
                   'p_relevante'                 =>array(tvl($p_relevante),                                B_VARCHAR,         1),
                   'p_sq_siw_solicitacao'        =>array(tvl($p_sq_siw_solicitacao),                       B_INTEGER,        32),
                   'p_cod_programa'              =>array(tvl($p_cod_programa),                             B_VARCHAR,        50),
                   'p_cod_acao'                  =>array(tvl($p_cod_acao),                                 B_VARCHAR,        50),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        60),                   
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
