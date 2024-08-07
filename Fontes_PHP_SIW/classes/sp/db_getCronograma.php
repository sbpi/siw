<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getCronograma
*
* { Description :- 
*    Recupera um atendimento de abastecimento
* }
*/

class db_getCronograma {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_inicio, $p_fim, $p_solic_apoio, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getCronograma';
     $params=array('p_chave'                =>array(tvl($p_chave),                        B_INTEGER,        18),
                   'p_chave_aux'            =>array(tvl($p_chave_aux),                    B_INTEGER,        18),
                   'p_inicio'               =>array(tvl($p_inicio),                       B_DATE,           32),
                   'p_fim'                  =>array(tvl($p_fim),                          B_DATE,           32),
                   'p_solic_apoio'          =>array(tvl($p_solic_apoio),                  B_INTEGER,        32),
                   'p_restricao'            =>array(tvl($p_restricao),                    B_VARCHAR2,       20),
                   'p_result'               =>array(null,                                 B_CURSOR,         -1)
                  );
     //print_r($params);             
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
