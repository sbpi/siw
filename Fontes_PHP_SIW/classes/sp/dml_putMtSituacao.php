<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putMeioTrans
*
* { Description :- 
*    Mantém a tabela de companhias de viagem
* }
*/

class dml_putMtSituacao {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_nome, $p_sigla, $p_entrada, $p_saida, $p_estorno,$p_consumo,$p_permanente,$p_inativa_bem,$p_situacao_fisica, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putMtSituacao';
     $params=array('p_operacao'          =>array($operacao,                       B_VARCHAR,         1),
                   'p_cliente'           =>array(tvl($p_cliente),                 B_INTEGER,        32),
                   'p_chave'             =>array(tvl($p_chave),                   B_INTEGER,        32),
                   'p_nome'              =>array(tvl($p_nome),                    B_VARCHAR,        60),
                   'p_sigla'             =>array(tvl($p_sigla),                   B_VARCHAR,         2),
                   'p_entrada'           =>array(tvl($p_entrada),                 B_VARCHAR,         1),
                   'p_saida'             =>array(tvl($p_saida),                   B_VARCHAR,         1),
                   'p_estorno'           =>array(tvl($p_estorno),                 B_VARCHAR,         1),
		           'p_consumo'           =>array(tvl($p_consumo),                 B_VARCHAR,         1),
			       'p_permanente'        =>array(tvl($p_permanente),              B_VARCHAR,         1),
				   'p_inativa_bem'       =>array(tvl($p_inativa_bem),             B_VARCHAR,         1),
				   'p_situacao_fisica'   =>array(tvl($p_situacao_fisica),         B_VARCHAR,         1),				  
                   'p_ativo'             =>array(tvl($p_ativo),                   B_VARCHAR,         1)
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
