<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getLancamento
*
* { Description :- 
*    Recupera os dados para os relatórios de contas a pagar, a receber e fluxo de caixa
* }
*/

class db_getLancamento {
   function getInstanceOf($dbms, $p_cliente, $p_restricao, 
        $p_dt_ini, $p_dt_fim, $p_pg_ini, $p_pg_fim, $p_co_ini, $p_co_fim, 
        $p_sq_pessoa, $p_projeto, $p_cadastramento, $p_pago) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getLancamento';
     $params=array('p_cliente'                  =>array($p_cliente,                   B_INTEGER,        32),
                   'p_restricao'                =>array(tvl($p_restricao),            B_VARCHAR,        50),
                   'p_dt_ini'                   =>array(tvl($p_dt_ini),               B_DATE,           32),
                   'p_dt_fim'                   =>array(tvl($p_dt_fim),               B_DATE,           32),
                   'p_pg_ini'                   =>array(tvl($p_pg_ini),               B_DATE,           32),
                   'p_pg_fim'                   =>array(tvl($p_pg_fim),               B_DATE,           32),
                   'p_co_ini'                   =>array(tvl($p_co_ini),               B_DATE,           32),
                   'p_co_fim'                   =>array(tvl($p_co_fim),               B_DATE,           32),
                   'p_sq_pessoa'                =>array(tvl($p_sq_pessoa),            B_INTEGER,        32),
                   'p_projeto'                  =>array(tvl($p_projeto),              B_INTEGER,        32),
                   'p_cadastramento'            =>array(tvl($p_cadastramento),        B_VARCHAR,         1),
                   'p_pago'                     =>array(tvl($p_pago),                 B_VARCHAR,         1),
                   'p_result'                   =>array(null,                         B_CURSOR,         -1)
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
