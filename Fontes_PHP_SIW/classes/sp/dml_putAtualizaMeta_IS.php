<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putAtualizaMeta_IS
*
* { Description :- 
*    Atualiza uma meta da ação
* }
*/

class dml_putAtualizaMeta_IS {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_perc_conclusao, $p_situacao_atual, $p_exequivel, $p_justificativa_inex, $p_outras_medidas) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTATUALIZAMETA_IS';
     $params=array('p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_chave_aux'                 =>array($p_chave_aux,                                     B_INTEGER,        32),
                   'p_perc_conclusao'            =>array(toNumber($p_perc_conclusao),                      B_DOUBLE,         32),
                   'p_situacao_atual'            =>array(tvl($p_situacao_atual),                           B_VARCHAR,      4000),
                   'p_exequivel'                 =>array(tvl($p_exequivel),                                B_VARCHAR,         1),
                   'p_justificativa_inex'        =>array(tvl($p_justificativa_inex),                       B_VARCHAR,      4000),
                   'p_outras_medidas'            =>array(tvl($p_outras_medidas),                           B_VARCHAR,      4000)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
