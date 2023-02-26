<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putConfCL
*
* { Description :- 
*    Registra vínculos financeiros do projeto com relação a compras e licitações
* }
*/

class dml_putConfCL {
   function getInstanceOf($dbms, $p_operacao, $p_cliente, $p_menu, $p_solic, $p_chave, $p_rubrica, $p_lancamento, 
        $p_consumo, $p_permanente, $p_servico, $p_outros) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putConfCL';
     $params=array('p_operacao'               =>array($p_operacao,                              B_VARCHAR,         1),
                   'p_cliente'                =>array($p_cliente,                               B_INTEGER,        32),
                   'p_menu'                   =>array($p_menu,                                  B_INTEGER,        32),
                   'p_chave'                  =>array(tvl($p_chave),                            B_INTEGER,        32),
                   'p_solic'                  =>array(tvl($p_solic),                            B_INTEGER,        32),
                   'p_rubrica'                =>array(tvl($p_rubrica),                          B_INTEGER,        32),
                   'p_lancamento'             =>array(tvl($p_lancamento),                       B_INTEGER,        32),
                   'p_consumo'                =>array(tvl($p_consumo),                          B_VARCHAR,         1),
                   'p_permanente'             =>array(tvl($p_permanente),                       B_VARCHAR,         1),
                   'p_servico'                =>array(tvl($p_servico),                          B_VARCHAR,         1),
                   'p_outros'                 =>array(tvl($p_outros),                           B_VARCHAR,         1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
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
