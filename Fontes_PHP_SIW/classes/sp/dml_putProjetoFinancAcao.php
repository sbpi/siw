<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putProjetoFinancAcao
*
* { Description :- 
*    Mantém a tabela de financiamento da ação
* }
*/

class dml_putProjetoFinancAcao {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_sq_acao_ppa, $p_obs_financ) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTPROJETOFINANCACAO';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_acao_ppa'               =>array(tvl($p_sq_acao_ppa),                              B_INTEGER,        32),
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
