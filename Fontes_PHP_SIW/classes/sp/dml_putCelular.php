<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCelular
*
* { Description :- 
*    Mantém a tabela de celulares
* }
*/

class dml_putCelular {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_numero, $p_marca, $p_modelo, $p_sim_card, $p_imei, $p_acessorios, 
           $p_bloqueio, $p_inicio, $p_motivo, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql = $strschema.'sp_putCelular';
     $params = array('p_operacao'             =>array($operacao,                                  B_VARCHAR,         1),
                     'p_cliente'              =>array($p_cliente,                                 B_INTEGER,        18),
                     'p_chave'                =>array(tvl($p_chave),                              B_INTEGER,        18),
                     'p_numero'               =>array(tvl($p_numero),                             B_VARCHAR,        20),
                     'p_marca'                =>array(tvl($p_marca),                              B_VARCHAR,        40),
                     'p_modelo'               =>array(tvl($p_modelo),                             B_VARCHAR,        40),
                     'p_sim_card'             =>array(tvl($p_sim_card),                           B_VARCHAR,        25),
                     'p_imei'                 =>array(tvl($p_imei),                               B_VARCHAR,        25),
                     'p_acessorios'           =>array(tvl($p_acessorios),                         B_VARCHAR,      1000),
                     'p_bloqueio'             =>array(tvl($p_bloqueio),                           B_VARCHAR,         1),
                     'p_inicio'               =>array(tvl($p_inicio),                             B_DATE,           32),
                     'p_motivo'               =>array(tvl($p_motivo),                             B_VARCHAR,      1000),
                     'p_ativo'                =>array(tvl($p_ativo),                              B_VARCHAR,         1)
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
