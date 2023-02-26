<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getAfastamento
*
* { Description :- 
*    Recupera os afastamentos
* }
*/

class db_getAfastamento {
   function getInstanceOf($dbms, $p_cliente, $p_pessoa, $p_chave, $p_sq_tipo_afastamento, $p_sq_contrato_colaborador, $p_inicio_data, $p_fim_data, $p_periodo_inicio, $p_periodo_fim, $p_chave_aux, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_GETAFASTAMENTO';
     $params=array('p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_pessoa'                    =>array(tvl($p_pessoa),                                   B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_tipo_afastamento'       =>array(tvl($p_sq_tipo_afastamento),                      B_INTEGER,        32),
                   'p_sq_contrato_colaborador'   =>array(tvl($p_sq_contrato_colaborador),                  B_INTEGER,        32),
                   'p_inicio_data'               =>array(tvl($p_inicio_data),                              B_DATE,           32),
                   'p_fim_data'                  =>array(tvl($p_fim_data),                                 B_DATE,           32),
                   'p_periodo_inicio'            =>array(tvl($p_periodo_inicio),                           B_VARCHAR,         1),
                   'p_periodo_fim'               =>array(tvl($p_periodo_fim),                              B_VARCHAR,         1),
                   'p_chave_aux'                 =>array(tvl($p_chave_aux),                                B_INTEGER,        32),
                   'p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        20),
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
