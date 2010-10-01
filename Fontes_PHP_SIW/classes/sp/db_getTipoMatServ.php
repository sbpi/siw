<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getTipoMatServ
*
* { Description :- 
*    Recupera dados da tabela de tipos de materiais e serviços
* }
*/

class db_getTipoMatServ {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_chave_pai, $p_nome, $p_sigla, $p_gestora, $p_ativo, $p_classe, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_PE.'sp_getTipoMatServ';
     $params=array('p_cliente'      =>array($p_cliente,         B_INTEGER,        32),
                   'p_chave'        =>array(tvl($p_chave),      B_INTEGER,        32),
                   'p_chave_pai'    =>array($p_chave_pai,       B_NUMERIC,        32),
                   'p_nome'         =>array(tvl($p_nome),       B_VARCHAR,        60),
                   'p_sigla'        =>array(tvl($p_sigla),      B_VARCHAR,        15),
                   'p_gestora'      =>array(tvl($p_gestora),    B_INTEGER,        32),
                   'p_ativo'        =>array(tvl($p_ativo),      B_VARCHAR,         1),
                   'p_classe'       =>array(tvl($p_classe),     B_INTEGER,        32),
                   'p_restricao'    =>array(tvl($p_restricao),  B_VARCHAR,        15),
                   'p_result'       =>array(null,               B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
