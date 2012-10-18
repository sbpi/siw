<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSolicApoio
*
* { Description :- 
*    Mantém a tabela de fontes de financiamento de uma solicitação
* }
*/

class dml_putSolicApoio {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_tipo_apoio, $p_entidade, $p_descricao, $p_valor, $p_pessoa) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');  $sql=$strschema.'sp_putSolicApoio';
     $params=array('p_operacao'           =>array($operacao,                            B_VARCHAR,         1),
                   'p_chave'              =>array(tvl($p_chave),                        B_INTEGER,        32),
                   'p_chave_aux'          =>array(tvl($p_chave_aux),                    B_INTEGER,        32),
                   'p_tipo_apoio'         =>array(tvl($p_tipo_apoio),                   B_INTEGER,        32),
                   'p_entidade'           =>array(tvl($p_entidade),                     B_VARCHAR,        50),
                   'p_descricao'          =>array(tvl($p_descricao),                    B_VARCHAR,       200),
                   'p_valor'              =>array(tvl($p_valor),                        B_NUMERIC,     18, 2),
                   'p_pessoa'             =>array(tvl($p_pessoa),                       B_INTEGER,        32)
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
