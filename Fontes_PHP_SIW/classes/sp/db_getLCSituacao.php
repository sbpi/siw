<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getLCSituacao
*
* { Description :- 
*    Recupera as situações de um certame
* }
*/

class db_getLCSituacao {
   function getInstanceOf($dbms, $p_chave, $p_cliente, $p_nome, $p_ativo, $p_padrao, $p_publicar, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getLCSituacao';
     $params=array('p_chave'             =>array(tvl($p_chave),                            B_INTEGER,        32),
                   'p_cliente'           =>array($p_cliente,                               B_INTEGER,        32),
                   'p_nome'              =>array(tvl($p_nome),                             B_VARCHAR,        60),
                   'p_ativo'             =>array(tvl($p_ativo),                            B_VARCHAR,         1),
                   'p_padrao'            =>array(tvl($p_padrao),                           B_VARCHAR,         1),
                   'p_publicar'          =>array(tvl($p_publicar),                         B_VARCHAR,         1),                 
                   'p_restricao'         =>array(tvl($p_restricao),                        B_VARCHAR,        15),
                   'p_result'            =>array(null,                                     B_CURSOR,         -1)
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
