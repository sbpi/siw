<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCTEspecificacao
*
* { Description :- 
*    Mantém a tabela de especificações de despesa
* }
*/

class dml_putCTEspecificacao {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave, $p_chave_pai, $p_sq_cc, $p_ano, $p_codigo, $p_nome, $p_valor, $p_ultimo_nivel, $p_ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTCTEspecificacao';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),     
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_chave_pai'                 =>array(tvl($p_chave_pai),                                B_INTEGER,        32),
                   'p_sq_cc'                     =>array(tvl($p_sq_cc),                                    B_INTEGER,        32),
                   'p_ano'                       =>array(tvl($p_ano),                                      B_VARCHAR,         4),
                   'p_codigo'                    =>array(tvl($p_codigo),                                   B_VARCHAR,        10),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        70),
                   'p_valor'                     =>array(toNumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_ultimo_nivel'               =>array(tvl($p_ultimo_nivel),                            B_VARCHAR,         1),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1)
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
