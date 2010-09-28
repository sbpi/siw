<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putFinancAcaoPPA_IS
*
* { Description :- 
*    Mantém a tabela de financiamento da ação
* }
*/

class dml_putFinancAcaoPPA_IS {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_programa, $p_acao, $p_subacao, $cliente, $ano, $p_obs_financ) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTFINANCACAOPPA_IS';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cd_programa'               =>array(tvl($p_programa),                                 B_VARCHAR,         4),
                   'p_cd_acao'                   =>array(tvl($p_acao),                                     B_VARCHAR,         4),
                   'p_cd_subacao'                =>array(tvl($p_subacao),                                  B_VARCHAR,         4),
                   'p_cliente'                   =>array(tvl($cliente),                                    B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($ano),                                        B_INTEGER,        32),
                   'p_obs_financ'                =>array(tvl($p_obs_financ),                               B_VARCHAR,      2000)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
