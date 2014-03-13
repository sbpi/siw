<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Dados
*
* { Description :- 
*    Grava valor de reembolso de viagem.
* }
*/

class dml_putPD_Dados {
   function getInstanceOf($dbms, $p_chave, $p_fim_semana, $p_complemento_qtd, $p_moeda, $p_complemento_base, $p_complemento_valor) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_Dados';
     $params=array('p_chave'                   =>array(tvl($p_chave),                         B_INTEGER,        32),
                   'p_fim_semana'              =>array(tvl($p_fim_semana),                    B_VARCHAR,         1),
                   'p_complemento_qtd'         =>array(toNumber(tvl($p_complemento_qtd)),     B_NUMERIC,       5,1),
                   'p_moeda'                   =>array(tvl($p_moeda),                         B_INTEGER,        32),
                   'p_complemento_base'        =>array(toNumber(tvl($p_complemento_base)),    B_NUMERIC,      18,2),
                   'p_complemento_valor'       =>array(toNumber(tvl($p_complemento_valor)),   B_NUMERIC,      18,2)
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
