<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getPAElimItem
*
* { Description :- 
*    Recupera os itens de uma solicitação de eliminação
* }
*/

class db_getPAElimItem {
   function getInstanceOf($dbms, $p_chave, $p_solicitacao, $p_atraso, $p_ini, $p_fim, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getPAElimItem';
     $params=array('p_chave'                    =>array($p_chave,                               B_INTEGER,        32),
                   'p_solicitacao'              =>array($p_solicitacao,                         B_INTEGER,        32),
                   'p_atraso'                   =>array(tvl($p_atraso),                         B_VARCHAR,         1),
                   'p_ini'                      =>array(tvl($p_ini),                            B_DATE,           32),
                   'p_fim'                      =>array(tvl($p_fim),                            B_DATE,           32),
                   'p_restricao'                =>array($p_restricao,                           B_VARCHAR,        20),
                   'p_result'                   =>array(null,                                   B_CURSOR,         -1)
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
