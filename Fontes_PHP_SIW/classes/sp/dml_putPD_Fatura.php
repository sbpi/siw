<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Fatura
*
* { Description :- 
*    Grava faturas eletrônicas da agência de viagens
* }
*/

class dml_putPD_Fatura {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_arquivo, $p_agencia, $p_tipo, $p_numero, $p_inicio, $p_fim, $p_emissao, 
          $p_vencimento, $p_valor, $p_registros, $p_importados, $p_rejeitados, $p_chave_nova) {

     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_Fatura';
     $params=array('p_operacao'         => array($operacao,                     B_VARCHAR,         1),
                   'p_chave'            => array(tvl($p_chave),                 B_INTEGER,        32),
                   'p_arquivo'          => array(tvl($p_arquivo),               B_INTEGER,        32),
                   'p_agencia'          => array(tvl($p_agencia),               B_INTEGER,        32),
                   'p_tipo'             => array(tvl($p_tipo),                  B_INTEGER,         1),
                   'p_numero'           =>array(tvl($p_numero),                 B_VARCHAR,        20),
                   'p_inicio'           =>array(tvl($p_inicio),                 B_DATE,           32),
                   'p_fim'              =>array(tvl($p_fim),                    B_DATE,           32),
                   'p_emissao'          =>array(tvl($p_emissao),                B_DATE,           32),
                   'p_vencimento'       =>array(tvl($p_vencimento),             B_DATE,           32),
                   'p_valor'            =>array(toNumber(tvl($p_valor)),        B_NUMERIC,      18,2),
                   'p_registros'        => array(tvl($p_registros),             B_INTEGER,        32),
                   'p_importados'       => array(tvl($p_importados),            B_INTEGER,        32),
                   'p_rejeitados'       => array(tvl($p_rejeitados),            B_INTEGER,        32),
                   'p_chave_nova'       =>array(&$p_chave_nova,                 B_INTEGER,        32)
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
