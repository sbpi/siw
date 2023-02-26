<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putTipoLancamento
*
* { Description :- 
*    Mantém a tabela de tipos de lançamentos financeiros
* }
*/

class dml_putTipoLancamento {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_pai, $p_cliente, $p_nome, $p_descricao, $p_receita, $p_despesa, $p_reembolso, $p_codigo_externo, $p_ativo, &$p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putTipoLancamento';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_pai'                       =>array(tvl($p_pai),                                      B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,       200),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,       200),
                   'p_receita'                   =>array(tvl($p_receita),                                  B_VARCHAR,         1),
                   'p_despesa'                   =>array(tvl($p_despesa),                                  B_VARCHAR,         1),
                   'p_reembolso'                 =>array(tvl($p_reembolso),                                B_VARCHAR,         1),
                   'p_codigo_externo'            =>array(tvl($p_codigo_externo),                           B_VARCHAR,        60),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
                   'p_chave_nova'                =>array(&$p_chave_nova,                                   B_INTEGER,        32)
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
