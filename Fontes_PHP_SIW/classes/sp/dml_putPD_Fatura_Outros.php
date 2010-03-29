<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Fatura_Outros
*
* { Description :- 
*    Grava registros de faturas eletrônicas da agência de viagens relacionados a hospedagens, locações de veículo e seguros de viagem
* }
*/

class dml_putPD_Fatura_Outros {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_solic, $p_fatura, $p_tipo, $p_cnpj, $p_nome, $p_inicio, $p_fim, $p_valor) {

     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_Fatura_Outros';
     $params=array('p_operacao'         => array($operacao,                     B_VARCHAR,         1),
                   'p_cliente'          => array($p_cliente,                    B_INTEGER,        32),
                   'p_chave'            => array(tvl($p_chave),                 B_INTEGER,        32),
                   'p_solic'            => array(tvl($p_solic),                 B_INTEGER,        32),
                   'p_fatura'           => array(tvl($p_fatura),                B_INTEGER,        32),
                   'p_tipo'             => array(tvl($p_tipo),                  B_INTEGER,         1),
                   'p_cnpj'             =>array(tvl($p_cnpj),                   B_VARCHAR,        20),
                   'p_nome'             =>array(tvl($p_nome),                   B_VARCHAR,        60),
                   'p_inicio'           =>array(tvl($p_inicio),                 B_DATE,           32),
                   'p_fim'              =>array(tvl($p_fim),                    B_DATE,           32),
                   'p_valor'            =>array(toNumber(tvl($p_valor)),        B_NUMERIC,      18,2)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
