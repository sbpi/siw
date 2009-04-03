<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getGPTipoAfast
*
* { Description :- 
*    Recupera os tipos de afastamento
* }
*/

class db_getGPTipoAfast {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_sigla, $p_nome, $p_ativo, $p_chave_aux, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'sp_getGPTipoAfast';
     $params=array('p_cliente'             =>array($p_cliente,                  B_INTEGER,        32),
                   'p_chave'               =>array(tvl($p_chave),               B_INTEGER,        32),
                   'p_sigla'               =>array(tvl($p_sigla),               B_VARCHAR,         3),
                   'p_nome'                =>array(tvl($p_nome),                B_VARCHAR,        50),
                   'p_ativo'               =>array(tvl($p_ativo),               B_VARCHAR,         1),
                   'p_chave_aux'           =>array(tvl($p_chave_aux),           B_INTEGER,        32),
                   'p_restricao'           =>array(tvl($p_restricao),           B_VARCHAR,        20),
                   'p_result'              =>array(null,                        B_CURSOR,         -1)
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
