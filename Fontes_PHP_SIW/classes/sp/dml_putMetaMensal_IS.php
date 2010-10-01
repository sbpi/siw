<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMetaMensal_IS
*
* { Description :- 
*    Mantém a tabela de atualzação mensal das metas de uma ação
* }
*/

class dml_putMetaMensal_IS {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_realizado, $p_revisado, $p_referencia, $p_cliente) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_is.'SP_PUTMETAMENSAL_IS';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_realizado'                 =>array(toNumber(tvl($p_realizado)),                      B_NUMERIC,      18,4),
                   'p_revisado'                  =>array(toNumber(tvl($p_revisado)),                       B_NUMERIC,      18,4),
                   'p_referencia'                =>array(tvl($p_referencia),                               B_DATE,           32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32)
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
