<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putDadosAcaoPPA_IS
*
* { Description :- 
*    Mantém os dados do SIAFI na tabela de ações do SIGPLAN
* }
*/

class dml_putDadosAcaoPPA_IS {
   function getInstanceOf($dbms, $p_cliente, $p_ano, $p_unidade, $p_programa, $p_acao, $p_subacao, $p_aprovado, $p_empenhado, $p_liquidado) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTDADOSACAOPPA_IS';
     $params=array('p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_INTEGER,        32),
                   'p_unidade'                   =>array(tvl($p_unidade),                                  B_VARCHAR,         5),
                   'p_programa'                  =>array(tvl($p_programa),                                 B_VARCHAR,         4),
                   'p_acao'                      =>array(tvl($p_acao),                                     B_VARCHAR,         4),
                   'p_subacao'                   =>array(tvl($p_subacao),                                  B_VARCHAR,         4),
                   'p_aprovado'                  =>array(toNumber(tvl($p_aprovado)),                       B_NUMERIC,      18,2),
                   'p_empenhado'                 =>array(toNumber(tvl($p_empenhado)),                      B_NUMERIC,      18,2),
                   'p_liquidado'                 =>array(toNumber(tvl($p_liquidado)),                      B_NUMERIC,      18,2)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
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
