<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPrestacaoContas
*
* { Description :- 
*    Mantém a tabela de prestacao de contas
* }
*/

class dml_putPrestacaoContas {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_chave_pai, $p_nome, $p_descricao, $p_tipo, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema_PE.'SP_PUTPRESTACAOCONTAS';
     $params=array('p_operacao'        =>array($operacao,                            B_VARCHAR,         1),
                   'p_cliente'         =>array(tvl($p_cliente),                      B_INTEGER,        32),
                   'p_chave'           =>array(tvl($p_chave),                        B_INTEGER,        32),
                   'p_chave_pai'       =>array(tvl($p_chave_pai),                    B_INTEGER,        32),
                   'p_nome'            =>array(tvl($p_nome),                         B_VARCHAR,        60),
                   'p_descricao'       =>array(tvl($p_descricao),                    B_VARCHAR,      2000),
                   'p_tipo'            =>array(tvl($p_tipo),                         B_VARCHAR,         1),
                   'p_ativo'           =>array(tvl($p_ativo),                        B_VARCHAR,         1)
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
