<?php
extract($GLOBALS); 
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getAssunto_PA
*
* { Description :- 
*    Recupera os assuntos cadastrados
* }
*/

class db_getAssunto_PA {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_chave_pai, $p_codigo, $p_descricao, $p_corrente_guarda, $p_intermed_guarda, $p_final_guarda, $p_destinacao_final, $p_ativo, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETASSUNTO_PA';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_pai'                 =>array(tvl($p_chave_pai),                                B_INTEGER,        32),
                   'p_codigo'                    =>array($p_codigo,                                        B_VARCHAR,        10),
                   'p_descricao'                 =>array($p_descricao,                                     B_VARCHAR,       255),
                   'p_corrente_guarda'           =>array(tvl($p_corrente_guarda),                          B_INTEGER,        32),
                   'p_intemed_guarda'            =>array(tvl($p_intemed_guarda),                           B_INTEGER,        32),
                   'p_final_guarda'              =>array(tvl($p_final_guarda),                             B_INTEGER,        32),
                   'p_destinacao_final'          =>array(tvl($p_destinacao_final),                         B_INTEGER,        32),
                   'p_ativo'                     =>array($p_ativo,                                         B_VARCHAR,         1),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        20),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
