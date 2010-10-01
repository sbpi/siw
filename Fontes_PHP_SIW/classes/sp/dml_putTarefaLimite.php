<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putTarefaLimite
*
* { Description :- 
*    Atualiza o limite orçamentário da tarefa
* }
*/

class dml_putTarefaLimite {
   function getInstanceOf($dbms, $p_chave, $p_pessoa, $p_tramite, $p_custo_real) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTTAREFALIMITE';
     $params=array('p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_pessoa'                    =>array(tvl($p_pessoa),                                   B_INTEGER,        32),
                   'p_tramite'                   =>array(tvl($p_tramite),                                  B_INTEGER,        32),
                   'p_custo_real'                =>array(toNumber(tvl($p_custo_real)),                     B_NUMERIC,      18,2)
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
